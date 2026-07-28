<?php
declare(strict_types=1);

namespace JlacroixDev\PdoRow\Repository\PDO\MySQL\TableRow;

/**
 * This file is generated automatically by jlacroix-dev/pdo-row
 */
class TablesTableRow
{
    // varchar(64) NOT NULL
    public string $TABLE_CATALOG;

    // varchar(64) NOT NULL
    public string $TABLE_SCHEMA;

    // varchar(64) NOT NULL
    public string $TABLE_NAME;

    // enum('BASE TABLE','VIEW','SYSTEM VIEW') NOT NULL
    public string $TABLE_TYPE;

    // varchar(64) NULL
    public ?string $ENGINE;

    // int NULL
    public ?string $VERSION;

    // enum('Fixed','Dynamic','Compressed','Redundant','Compact','Paged') NULL
    public ?string $ROW_FORMAT;

    // bigint unsigned NULL
    public ?string $TABLE_ROWS;

    // bigint unsigned NULL
    public ?string $AVG_ROW_LENGTH;

    // bigint unsigned NULL
    public ?string $DATA_LENGTH;

    // bigint unsigned NULL
    public ?string $MAX_DATA_LENGTH;

    // bigint unsigned NULL
    public ?string $INDEX_LENGTH;

    // bigint unsigned NULL
    public ?string $DATA_FREE;

    // bigint unsigned NULL
    public ?string $AUTO_INCREMENT;

    // timestamp NOT NULL
    public string $CREATE_TIME;

    // datetime NULL
    public ?string $UPDATE_TIME;

    // datetime NULL
    public ?string $CHECK_TIME;

    // varchar(64) NULL
    public ?string $TABLE_COLLATION;

    // bigint NULL
    public ?string $CHECKSUM;

    // varchar(256) NULL
    public ?string $CREATE_OPTIONS;

    // text NULL
    public ?string $TABLE_COMMENT;

}
