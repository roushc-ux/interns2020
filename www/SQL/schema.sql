CREATE TABLE users (
    username VARCHAR(40) NOT NULL,
    email VARCHAR (40) NOT NULL,
    password VARCHAR (256) NOT NULL,
    PRIMARY KEY (username)
);

CREATE TABLE onlineUsers(
    username VARCHAR (40) NOT NULL,
    gameID int,
    FOREIGN KEY username REFERENCES users(username),
    PRIMARY KEY (username)
)

CREATE TABLE games(
    gameID int NOT NULL,
    deck INTEGER ARRAY[52],
    PRIMARY KEY (gameID)
)