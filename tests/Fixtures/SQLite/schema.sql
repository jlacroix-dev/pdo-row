DROP TABLE IF EXISTS users;

CREATE TABLE users
(
    id         INTEGER      NOT NULL PRIMARY KEY,
    name       VARCHAR(255) NOT NULL,
    email      VARCHAR(255) NOT NULL,
    active     BOOLEAN      NOT NULL,
    nickname   VARCHAR(255) NULL,
    created_at DATETIME     NOT NULL
);

INSERT INTO users (id,
                   name,
                   email,
                   active,
                   nickname,
                   created_at)
VALUES (1,
        'John Doe',
        'john@example.com',
        1,
        NULL,
        '2026-08-08 12:00:00'),
       (2,
        'Jane Doe',
        'jane@example.com',
        0,
        'Jane',
        '2026-08-08 13:00:00');

DROP TABLE IF EXISTS t1;

CREATE TABLE t1
(
    text_col    TEXT    NULL,
    numeric_col NUMERIC NULL,
    integer_col INTEGER NULL,
    real_col    REAL    NULL,
    blob_col    BLOB    NULL
);

INSERT INTO t1 (text_col,
                numeric_col,
                integer_col,
                real_col,
                blob_col)
VALUES (NULL,
        NULL,
        NULL,
        NULL,
        NULL),
       ('aaa',
        123,
        456,
        2.3,
        'bbb'),
       ('123',
        2.3,
        456.5,
        2,
        'bbb');

