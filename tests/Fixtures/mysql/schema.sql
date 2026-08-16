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

INSERT INTO users (id, name, email, active, nickname, created_at)
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

-- Numeric --

DROP TABLE IF EXISTS numeric_types;

CREATE TABLE numeric_types
(
    bit_col       BIT(6)        NULL,
    tinyint_col   TINYINT       NULL,
    bool_col      BOOL          NULL,
    boolean_col   BOOLEAN       NULL,
    smallint_col  SMALLINT      NULL,
    mediumint_col MEDIUMINT     NULL,
    int_col       INT           NULL,
    integer_col   INTEGER       NULL,
    bigint_col    BIGINT        NULL,
    decimal_col   DECIMAL(5, 2) NULL,
    dec_col       DEC(5, 2)     NULL,
    float_col     FLOAT(7, 4)   NULL,
    double_col    DOUBLE(7, 4)  NULL
) ENGINE = InnoDB;

INSERT INTO numeric_types (bit_col,
                           tinyint_col,
                           bool_col,
                           boolean_col,
                           smallint_col,
                           mediumint_col,
                           int_col,
                           integer_col,
                           bigint_col,
                           decimal_col,
                           dec_col,
                           float_col,
                           double_col)
VALUES (null,
        null,
        null,
        null,
        null,
        null,
        null,
        null,
        null,
        null,
        null,
        null,
        null),
#     min value
       (b'0',
        -128,
        0,
        0,
        -32768,
        -8388608,
        -2147483648,
        -2147483648,
        -9223372036854775808,
        -999.99,
        -999.99,
        -999.9999,
        -999.9999),
#     value
       (b'101',
        -10,
        1,
        0,
        11,
        12,
        13,
        14,
        15,
        16.5,
        17.5,
        18.5,
        19.5),
#     max value
       (b'11111',
        127,
        1,
        1,
        32767,
        8388607,
        2147483647,
        2147483647,
        9223372036854775807,
        999.99,
        999.99,
        999.9999,
        999.9999);

-- Date and Time --

DROP TABLE IF EXISTS date_and_time_types;

CREATE TABLE date_and_time_types
(
    date_col      DATE      NULL,
    datetime_col  DATETIME  NULL,
    timestamp_col TIMESTAMP NULL,
    time_col      TIME      NULL,
    year_col      YEAR      NULL
) ENGINE = InnoDB;

INSERT INTO date_and_time_types (date_col,
                                 datetime_col,
                                 timestamp_col,
                                 time_col,
                                 year_col)
VALUES (null,
        null,
        null,
        null,
        null),
#     min value
       ('1000-01-01',
        '1000-01-01 00:00:00.000000',
        '1970-01-01 00:00:01.000000',
        '-838:59:59.000000',
        '1901'),
#     value
       ('2026-08-15',
        '2026-08-15 12:05:36.499999',
        '2026-08-15 12:05:36.499999',
        '1:25:00.000000',
        2026),
#     max value
       ('9999-12-31',
        '9999-12-31 23:59:59.499999',
        '2038-01-19 03:14:07.499999',
        '838:59:59.000000',
        '2155');

-- String --
# https://dev.mysql.com/doc/refman/8.0/en/string-type-syntax.html
# DROP TABLE IF EXISTS string_types;
#
# CREATE TABLE string_types
# (
#     char_col      CHAR      NULL,
#     varchar_col  VARCHAR  NULL,
#     binary_col BINARY NULL,
#     varbinary_col      VARBINARY      NULL,
#     blob_col      BLOB      NULL,
#     text_col      TEXT      NULL,
#     enum_col      ENUM      NULL,
#     set_col      SET      NULL,
#
# ) ENGINE = InnoDB;
#
# INSERT INTO string_types (date_col,
#                           datetime_col,
#                           timestamp_col,
#                           time_col,
#                           year_col)
# VALUES (null,
#         null,
#         null,
#         null,
#         null),
# #     min value
#        ('1000-01-01',
#         '1000-01-01 00:00:00.000000',
#         '1970-01-01 00:00:01.000000',
#         '-838:59:59.000000',
#         '1901'),
# #     value
#        ('2026-08-15',
#         '2026-08-15 12:05:36.499999',
#         '2026-08-15 12:05:36.499999',
#         '1:25:00.000000',
#         2026),
# #     max value
#        ('9999-12-31',
#         '9999-12-31 23:59:59.499999',
#         '2038-01-19 03:14:07.499999',
#         '838:59:59.000000',
#         '2155');
