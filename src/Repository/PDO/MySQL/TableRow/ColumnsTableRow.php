<?php

declare(strict_types=1);

namespace JlacroixDev\PdoRow\Repository\PDO\MySQL\TableRow;

/**
 * This file was generated automatically.
 *
 * Generator: jlacroix-dev/pdo-row
 * Version: dev
 *
 * DO NOT EDIT MANUALLY.
 */

/**
 * Use with PDO::FETCH_ASSOC
 * @phpstan-type ColumnsTableRowAssoc array{
 *      TABLE_CATALOG: string,
 *      TABLE_SCHEMA: string,
 *      TABLE_NAME: string,
 *      COLUMN_NAME: ?string,
 *      ORDINAL_POSITION: string,
 *      COLUMN_DEFAULT: ?string,
 *      IS_NULLABLE: string,
 *      DATA_TYPE: ?string,
 *      CHARACTER_MAXIMUM_LENGTH: ?string,
 *      CHARACTER_OCTET_LENGTH: ?string,
 *      NUMERIC_PRECISION: ?string,
 *      NUMERIC_SCALE: ?string,
 *      DATETIME_PRECISION: ?string,
 *      CHARACTER_SET_NAME: ?string,
 *      COLLATION_NAME: ?string,
 *      COLUMN_TYPE: string,
 *      COLUMN_KEY: string,
 *      EXTRA: ?string,
 *      PRIVILEGES: ?string,
 *      COLUMN_COMMENT: string,
 *      GENERATION_EXPRESSION: string,
 *      SRS_ID: ?string,
 * }
 */
final class ColumnsTableRow
{
    // varchar(64) NOT NULL
    public string $TABLE_CATALOG;
    // varchar(64) NOT NULL
    public string $TABLE_SCHEMA;
    // varchar(64) NOT NULL
    public string $TABLE_NAME;
    // varchar(64) NULL
    public ?string $COLUMN_NAME;
    // int unsigned NOT NULL
    public string $ORDINAL_POSITION;
    // text NULL
    public ?string $COLUMN_DEFAULT;
    // varchar(3) NOT NULL
    public string $IS_NULLABLE;
    // longtext NULL
    public ?string $DATA_TYPE;
    // bigint NULL
    public ?string $CHARACTER_MAXIMUM_LENGTH;
    // bigint NULL
    public ?string $CHARACTER_OCTET_LENGTH;
    // bigint unsigned NULL
    public ?string $NUMERIC_PRECISION;
    // bigint unsigned NULL
    public ?string $NUMERIC_SCALE;
    // int unsigned NULL
    public ?string $DATETIME_PRECISION;
    // varchar(64) NULL
    public ?string $CHARACTER_SET_NAME;
    // varchar(64) NULL
    public ?string $COLLATION_NAME;
    // mediumtext NOT NULL
    public string $COLUMN_TYPE;
    // enum('','PRI','UNI','MUL') NOT NULL
    public string $COLUMN_KEY;
    // varchar(256) NULL
    public ?string $EXTRA;
    // varchar(154) NULL
    public ?string $PRIVILEGES;
    // text NOT NULL
    public string $COLUMN_COMMENT;
    // longtext NOT NULL
    public string $GENERATION_EXPRESSION;
    // int unsigned NULL
    public ?string $SRS_ID;
}
