#include <sourcemod>
#include <sdktools>

#define PLUGIN_VERSION "2.0"
#define DB_CONF_NAME "l4dstats"

new String:DbPrefix[64] = "";
new Handle:db = INVALID_HANDLE;
new PlayerTotal = 0;

public Plugin:myinfo =
{
  name = "L4D Server World Wide Rank",
  author = "D1ceWard",
  description = "Changes server rank and number of players served.",
  version = PLUGIN_VERSION,
  url = "https://www.forgesparks.com"
};

public OnConfigsExecuted()
{
  // Init MySQL connections
  if (!ConnectDB())
  {
    SetFailState("Connecting to database failed. Read error log for further details.");
    return;
  }
}

public OnClientConnected()
{
  ServerRanking();
}

public OnClientDisconnect()
{
  ServerRanking();
}

public ServerRanking()
{
  decl String:query[64];
  Format(query, sizeof(query), "SELECT COUNT(*) FROM %splayers", DbPrefix);
  SQL_TQuery(db, GetPlayerTotal, query);

  GameRules_SetProp("m_iServerRank", 1, 4, 0, false);
  GameRules_SetProp("m_iServerPlayerCount", PlayerTotal, 4, 0, false);
}

// Generate total player amount.
public GetPlayerTotal(Handle:owner, Handle:hndl, const String:error[], any:data)
{
  if (hndl == INVALID_HANDLE)
  {
    LogError("GetPlayerTotal Query failed: %s", error);
    return;
  }

  while (SQL_FetchRow(hndl))
    PlayerTotal = SQL_FetchInt(hndl, 0);
}

bool:ConnectDB()
{
  if (db != INVALID_HANDLE)
    return true;

  if (SQL_CheckConfig(DB_CONF_NAME))
  {
    new String:Error[256];
    db = SQL_Connect(DB_CONF_NAME, true, Error, sizeof(Error));

    if (db == INVALID_HANDLE)
    {
      LogError("Failed to connect to database: %s", Error);
      return false;
    }
    else if (!SQL_FastQuery(db, "SET NAMES 'utf8'"))
    {
      if (SQL_GetError(db, Error, sizeof(Error)))
        LogError("Failed to update encoding to UTF8: %s", Error);
      else
        LogError("Failed to update encoding to UTF8: unknown");
    }

    if (!CheckDatabaseValidity(DbPrefix))
    {
      LogError("Database is missing required table or tables.");
      return false;
    }
  }
  else
  {
    LogError("Databases.cfg missing '%s' entry!", DB_CONF_NAME);
    return false;
  }

  return true;
}

bool:CheckDatabaseValidity(const String:Prefix[])
{
  if (!DoFastQuery(0, "SELECT * FROM %splayers WHERE 1 = 2", Prefix))
  {
    return false;
  }

  return true;
}

bool:DoFastQuery(Client, const String:Query[], any:...)
{
  new String:FormattedQuery[4096];
  VFormat(FormattedQuery, sizeof(FormattedQuery), Query, 3);

  new String:Error[1024];

  if (!SQL_FastQuery(db, FormattedQuery))
  {
    if (SQL_GetError(db, Error, sizeof(Error)))
    {
      PrintToConsole(Client, "Fast query failed! (Error = \"%s\") Query = \"%s\"", Error, FormattedQuery);
      LogError("Fast query failed! (Error = \"%s\") Query = \"%s\"", Error, FormattedQuery);
    }
    else
    {
      PrintToConsole(Client, "Fast query failed! Query = \"%s\"", FormattedQuery);
      LogError("Fast query failed! Query = \"%s\"", FormattedQuery);
    }

    return false;
  }

  return true;
}
