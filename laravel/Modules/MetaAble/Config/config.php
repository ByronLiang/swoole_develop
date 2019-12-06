<?php

return [
    /*
     * List of handlers for recognized data types.
     *
     * Handlers will be evaluated in order, so a value will be handled
     * by the first appropriate handler in the list.
     */
    'datatypes' => [
        Modules\MetaAble\DataType\BooleanHandler::class,
        Modules\MetaAble\DataType\NullHandler::class,
        Modules\MetaAble\DataType\IntegerHandler::class,
        Modules\MetaAble\DataType\FloatHandler::class,
        Modules\MetaAble\DataType\StringHandler::class,
        Modules\MetaAble\DataType\DateTimeHandler::class,
        Modules\MetaAble\DataType\ArrayHandler::class,
        Modules\MetaAble\DataType\ModelHandler::class,
        Modules\MetaAble\DataType\ModelCollectionHandler::class,
        Modules\MetaAble\DataType\SerializableHandler::class,
        Modules\MetaAble\DataType\ObjectHandler::class,
    ],
];
