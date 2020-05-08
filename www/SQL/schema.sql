DROP DATABASE IF EXISTS internDatabase;
DROP DATABASE IF EXISTS blackjack;
CREATE DATABASE blackjack;
USE blackjack;
CREATE TABLE user (
    username VARCHAR(40) NOT NULL,
    email VARCHAR (40) NOT NULL,
    password VARCHAR (256) NOT NULL,
    wins INTEGER,
    PRIMARY KEY (username)
);

CREATE TABLE hand(
    handID INTEGER,
    PRIMARY KEY (handID)
);

CREATE TABLE online_user(
    username VARCHAR (40) NOT NULL,
    gameID INTEGER,
    money INTEGER,
    handID INTEGER,
    playerID INTEGER,
    FOREIGN KEY (handID) REFERENCES hand(handID),
    FOREIGN KEY (username) REFERENCES user(username),
    PRIMARY KEY (username)
);

CREATE TABLE game(
    gameID int NOT NULL,
    deckID INTEGER,
    discardID INTEGER,
    playerTurn INTEGER,
    numPlayers INTEGER,
    dealerHandID INTEGER,
    FOREIGN KEY (dealerHandID) REFERENCES hand(handID),
    PRIMARY KEY (gameID)
);

CREATE TABLE deck(
    deckID INTEGER,
    PRIMARY KEY (deckID)
);

CREATE TABLE card_deck(
    deckID INTEGER,
    cardID INTEGER,
    cardOrder INTEGER,
    FOREIGN KEY (deckID) REFERENCES deck(deckID),
    PRIMARY KEY (cardID, deckID)
);

CREATE TABLE discard(
    discardID INTEGER,
    PRIMARY KEY (discardID)
);

CREATE TABLE card_discard(
    discardID INTEGER,
    cardID INTEGER,
    FOREIGN KEY (discardID) REFERENCES discard(discardID),
    PRIMARY KEY (cardID, discardID)
);

CREATE TABLE card_hand(
    handID INTEGER,
    cardID INTEGER,
    FOREIGN KEY (handID) REFERENCES hand(handID),
    PRIMARY KEY (cardID)
);


