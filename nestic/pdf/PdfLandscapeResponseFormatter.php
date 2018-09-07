<?php

/**
 *
 * @author Ricardo Obregón <ricardo@obregon.co>
 * @created 15/05/14 12:35 PM
 */

namespace app\nestic\pdf;

use Yii;
use yii\base\Component;
use yii\web\Response;
use yii\web\ResponseFormatterInterface;

/**
 * PdfResponseFormatter formats the given HTML data into a PDF response content.
 *
 * It is used by [[Response]] to format response data.
 *
 * @author Ricardo Obregón <robregonm@gmail.com>
 * @since 2.0
 */
class PdfLandscapeResponseFormatter extends PdfResponseFormatter {

    public $orientation = self::ORIENT_LANDSCAPE;

}
