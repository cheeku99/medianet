Table Query:-

CREATE TABLE emailtoken(
ID INTEGER PRIMARY KEY AUTOINCREMENT,
token char(60),
generatedon DATETIME DEFAULT CURRENT_TIMESTAMP);


CREATE TABLE tokendata(
  ID INTEGER PRIMARY KEY AUTOINCREMENT,
  token char(60),
  ipaddress char(15),
  useragent text,
  referer text,
  otherinformation text,
  createdon DATETIME DEFAULT CURRENT_TIMESTAMP);

CREATE INDEX token_idx ON tokendata (token);


