<?php
namespace Controller;


class SnippetControllerBasic {

    // how many spaces is equal to one indent
    protected static int $INDENT_SPACES = 4;

    

    protected function prepareFields(array $fields, array $filterOut = []): array {

        // filter out unwanted fields
        foreach ($fields as &$field) {
            if (in_array($field['COLUMN_NAME'], $filterOut)) {
                $key = array_search($field['COLUMN_NAME'], $fields);
                unset($fields[$key]);
            }
        }

        // get length of longest DATA_TYPE and COLUMN_NAME
        $types = array_map(fn($e) => $e['DATA_TYPE_DISPLAY'], $fields);
        $maxTypeLen = max(array_map('strlen', $types));

        $names = array_map(fn($e) => $e['COLUMN_NAME'], $fields);
        $maxNameLen = max(array_map('strlen', $names));

        // add new keys to subarrays of $fields for correct spacing later
        foreach ($fields as &$field) {
            $field['DATA_TYPE_SPACES'] = str_repeat('&nbsp;', $maxTypeLen - strlen($field['DATA_TYPE_DISPLAY']));
            $field['COLUMN_NAME_SPACES'] = str_repeat('&nbsp;', $maxNameLen - strlen($field['COLUMN_NAME']));
        }
        return $fields;
    }

    protected function indent(int $level) {
        $indentSize = str_repeat('&nbsp;', self::$INDENT_SPACES);
        return str_repeat($indentSize, $level);
    }

    // remove only the last occurence of a substring in a string
    protected function removeLastOccurrence(string $string, string $search) {
        $offset = strrpos($string, $search);
        if ($offset !== false) {
            $length = strlen($search);
            $string = substr_replace($string, '', $offset, $length);
        }
        return $string;
    }

    protected function convertToClassname(string $tableName): string {
        $arr = explode('_', $tableName);
        for ($i = 0; $i < count($arr); $i++) { 
            if ($i == 0) {
                $arr[$i] = strtoupper($arr[$i]);
            } else if ($i == 1) {
                $arr[$i] = strtolower($arr[$i]);
            } else {
                $arr[$i] = ucfirst($arr[$i]);
            }
            
        }
        return implode('', $arr);
    }

}