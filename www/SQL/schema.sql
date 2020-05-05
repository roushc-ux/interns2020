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
);

CREATE TABLE games(
    gameID int NOT NULL,
    deckID INTEGER,
    discardID INTEGER,
    PRIMARY KEY (gameID)
);

CREATE TABLE decks(
    deckID INTEGER,
    PRIMARY KEY (deckID)
);

CREATE TABLE cardsDeck(
    deckID INTEGER,
    cardID INTEGER,
    order INTEGER,
    FOREIGN KEY deckID REFERENCES decks(deckID),
    PRIMARY KEY (cardID, deckID)
);

CREATE TABLE discards(
    discardID INTEGER,
    PRIMARY KEY (discardID)
);

CREATE TABLE cardsDiscard(
    discardID INTEGER,
    cardID INTEGER,
    order INTEGER,
    FOREIGN KEY discards REFERENCES decks(deckID),
    PRIMARY KEY (cardID, deckID)
);
