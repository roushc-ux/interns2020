USE blackjack;
INSERT INTO game (gameID, deckID, discardID, playerTurn, numPlayers, dealerHandID) VALUES (1, NULL, NULL, 0, 0, NULL);
INSERT INTO discard (discardID) VALUES (1);
INSERT INTO user (username, email, password, wins) VALUES ('a', 'a@a', '$2y$10$DFUcDJs8o20QoYNDdrBwxOALai8nUF768ukRTArEvKmWD9sQNArbG', 0);
INSERT INTO user (username, email, password, wins) VALUES ('b', 'b@b', '$2y$10$lctOnY1r0FQ34mHoWlaGXOBQv1.1HKIkEL5JuSNAjWONtNk3w6b2e', 0);
INSERT INTO user (username, email, password, wins) VALUES ('c', 'c@c', '$2y$10$GhyP88LbOEHwg8F.uo9PbOBXpYlHeb/O0Inv5BPGKxo3pa6wup82e', 0);
-- INSERT INTO deck (deckID) VALUES (1);