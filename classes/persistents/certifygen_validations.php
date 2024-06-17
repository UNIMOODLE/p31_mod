<?php

namespace mod_certifygen\persistents;

use coding_exception;
use core\invalid_persistent_exception;
use core\persistent;
use dml_exception;
use stdClass;

class certifygen_validations extends persistent {
    /**
     * @var string table
     */
    public const TABLE = 'certifygen_validations';
    public const STATUS_NOT_STARTED = 1;
    public const STATUS_IN_PROGRESS = 2;
    public const STATUS_FINISHED_OK = 3;
    public const STATUS_FINISHED_ERROR = 4;

    /**
     * Define properties
     *
     * @return array[]
     */
    protected static function define_properties(): array {
        return [
            'userid' => [
                'type' => PARAM_INT,
            ],
            'issueid' => [
                'type' => PARAM_INT,
                'default' => NULL,
                'null' => NULL_ALLOWED,
            ],
            'modelid' => [
                'type' => PARAM_INT,
            ],
            'status' => [
                'type' => PARAM_INT,
            ],
            'lang' => [
                'type' => PARAM_TEXT,
            ],
            'usermodified' => [
                'type' => PARAM_INT,
            ],
        ];
    }

    /**
     * @param int $id
     * @param stdClass $data
     * @return self
     * @throws coding_exception
     * @throws invalid_persistent_exception
     */
    public static function manage_validation(int $id, stdClass $data) : self {

        $validation = new self($id, $data);
        if (empty($id)) {
            $validation->create();
        } else {
            $validation->update();
        }
        return $validation;
    }

    /**
     * @param string $code
     * @return string
     * @throws dml_exception
     */
    public static function get_lang_by_code(string $code) : string {

        global $DB;
        $params = [
            'code' => $code,
            'component' => 'mod_certifygen'
        ];
        $sql = "SELECT cv.lang
                  FROM {tool_certificate_issues} ci
                  JOIN {certifygen_validations} cv
                    ON (cv.issueid = ci.id AND cv.userid = ci.userid)
                    WHERE code =:code AND component =:component";

        $record = $DB->get_record_sql($sql, $params);
        return $record->lang;
    }
}