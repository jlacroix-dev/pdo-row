DROP TABLE IF EXISTS users;

CREATE TABLE users
(
    id         INT UNSIGNED NOT NULL AUTO_INCREMENT,
    name       VARCHAR(255) NOT NULL,
    email      VARCHAR(255) NOT NULL,
    active     BOOLEAN      NOT NULL,
    nickname   VARCHAR(255) NULL,
    created_at DATETIME     NOT NULL,
    PRIMARY KEY (id)
) ENGINE = InnoDB;

INSERT INTO users (id,
                   name,
                   email,
                   active,
                   nickname,
                   created_at)
VALUES (1,
        'John Doe',
        'john@example.com',
        TRUE,
        NULL,
        '2026-08-08 12:00:00'),
       (2,
        'Jane Doe',
        'jane@example.com',
        FALSE,
        'Jane',
        '2026-08-08 13:00:00');
