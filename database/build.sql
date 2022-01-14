CREATE TABLE teams(
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  name VARCHAR(50) NOT NULL
);

CREATE TABLE matches(
  id INTEGER PRIMARY KEY AUTOINCREMENT, 
  team0 INTEGER NOT NULL, 
  team1 INTEGER NOT NULL,
  score0 INTEGER NOT NULL, 
  score1 INTEGER NOT NULL,
  date DATETIME,
  UNIQUE (team0, team1),
  FOREIGN KEY(team0, team1) REFERENCES teams(id, id),
);

CREATE TABLE teams(
  rank INTEGER UNIQUE,
  team_id INTEGER PRIMARY KEY,
  FOREIGN KEY (team_id) REFERENCES teams(id), 
  match_played_count INTEGER NOT NULL,
  won_match_count INTEGER NOT NULL, 
  lost_match_count INTEGER NOT NULL, 
  draw_match_count INTEGER NOT NULL,
  goal_for_count INTEGER NOT NULL, 
  goal_against_count INTEGER NOT NULL,
  goal_difference INTEGER NOT NULL,
  points INTEGER NOT NULL
);