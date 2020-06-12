<?php

// The SQL to uninstall this tool
$DATABASE_UNINSTALL = array(
"drop table if exists {$CFG->dbprefix}curriki"
);

// The SQL to create the tables if they don't exist
$DATABASE_INSTALL = array(
array( "{$CFG->dbprefix}curriki",
"create table {$CFG->dbprefix}curriki (
    link_id     INTEGER NOT NULL,
    user_id     INTEGER NOT NULL,
    entity      TEXT NOT NULL,
    entity_id       INTEGER NOT NULL,
    updated_at  DATETIME NOT NULL,

    CONSTRAINT `{$CFG->dbprefix}curriki_ibfk_1`
        FOREIGN KEY (`link_id`)
        REFERENCES `{$CFG->dbprefix}lti_link` (`link_id`)
        ON DELETE CASCADE ON UPDATE CASCADE,

    CONSTRAINT `{$CFG->dbprefix}curriki_ibfk_2`
        FOREIGN KEY (`user_id`)
        REFERENCES `{$CFG->dbprefix}lti_user` (`user_id`)
        ON DELETE CASCADE ON UPDATE CASCADE,

    UNIQUE(link_id, user_id)
) ENGINE = InnoDB DEFAULT CHARSET=utf8")
);

