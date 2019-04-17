<?php

/**
 *
 * @author  Ricardo Obregón <ricardo@obregon.co>
 * @created 15/05/14 12:35 PM
 */

namespace app\nestic\pdf;

use app\models\Empresa;
use Closure;
use mPDF;
use Yii;
use yii\base\Component;
use yii\helpers\Url;
use yii\web\Response;
use yii\web\ResponseFormatterInterface;

/**
 * PdfResponseFormatter formats the given HTML data into a PDF response content.
 *
 * It is used by [[Response]] to format response data.
 *
 * @author Ricardo Obregón <robregonm@gmail.com>
 * @since  2.0
 */
class PdfResponseFormatter extends Component implements ResponseFormatterInterface
{

    // mode
    const MODE_BLANK = '';
    const MODE_CORE = 'c';
    const MODE_UTF8 = 'utf-8';
    // format
    const FORMAT_A3 = 'A3';
    const FORMAT_A4 = 'A4';
    const FORMAT_LETTER = 'Letter';
    const FORMAT_LEGAL = 'Legal';
    const FORMAT_FOLIO = 'Folio';
    const FORMAT_LEDGER = 'Ledger-L';
    const FORMAT_TABLOID = 'Tabloid';
    // orientation
    const ORIENT_PORTRAIT = 'P';
    const ORIENT_LANDSCAPE = 'L';
    // output destination
    const DEST_BROWSER = 'I';
    const DEST_DOWNLOAD = 'D';
    const DEST_FILE = 'F';
    const DEST_STRING = 'S';

    public $mode = self::MODE_UTF8;
    public $format = self::FORMAT_LETTER;
    public $defaultFontSize = 0;
    public $defaultFont = '';
    public $marginLeft = 15;
    public $marginRight = 15;
    public $marginTop = 50;
    public $marginBottom = 20;
    public $marginHeader = 8;
    public $marginFooter = 8;
    public $orientation = self::ORIENT_PORTRAIT;
    public $output = self::DEST_STRING;

    /**
     * @var string 'Landscape' or 'Portrait'
     * Default to 'Portrait'
     */
    public $options = [];
    /**
     * @var Closure function($mpdf, $data){}
     */
    public $beforeRender;
    /**
     * @var mPDF api instance
     */
    protected $_mpdf;

    /**
     * Formats the specified response.
     *
     * @param Response $response the response to be formatted.
     */
    public function format($response)
    {
        $response->getHeaders()->set('Content-Type', 'application/pdf');
        $response->content = $this->formatPdf($response);
    }

    /**
     * Formats response HTML in PDF
     *
     * @param Response $response
     */
    protected function formatPdf($response)
    {
        $mpdf = $this->getApi();

        foreach ($this->options as $key => $option)
            if (property_exists($mpdf, $key))
                $mpdf->$key = $option;

        if ($this->beforeRender instanceof Closure)
            call_user_func($this->beforeRender, $mpdf, $response->data);

        $mpdf->SetHTMLHeader($this->getHeader());
        $mpdf->SetHTMLFooter($this->getFooter());
        $mpdf->WriteHTML($response->data);
        //        $mpdf->debug = true;
        return $mpdf->Output('', $this->output);
    }

    /**
     * Initializes (if needed) and fetches the mPDF API instance
     * @return mPDF instance
     */
    public function getApi()
    {
        if (empty($this->_mpdf) || !$this->_mpdf instanceof mPDF) {
            $this->setApi();
        }
        return $this->_mpdf;
    }

    /**
     * Sets the mPDF API instance
     */
    public function setApi()
    {
        $this->_mpdf = new mPDF($this->mode, $this->format . '-' . $this->orientation, $this->defaultFontSize, $this->defaultFont, $this->marginLeft, $this->marginRight, $this->marginTop, $this->marginBottom, $this->marginHeader, $this->marginFooter);
    }

    public function getHeader()
    {
        $empresa = Empresa::getModel();
        return '
       <table style="width:100%">
            <tr style="width:100%">
                <td style="font-size: 9pt; text-align: right;font-weight:bold; ">' . date("d/m/Y h:i A", time()) . '</td>
            </tr>
        </table>

        <table  style="color:#273b24; width:100%;">
            <tr style="width:100%">
                <td style="width:135px;">
                    <img style="width: 130px;" src="' . Url::to("@web/images/logo.jpg") . '" alt="' . $empresa->nombre . '">
                </td>
                <td style="text-align: center;">
                    <br>
                    <span style="font-weight:bold; font-size:14pt; ">' . $empresa->nombre . '</span>
                    <br>
                    <br>
                    <span style="font-size:9pt; ">' . $empresa->direccion . '</span>
                </td>
                <td style="width:145px;">
                    <img style="width: 140px;" src="' . Url::to("@web/images/logo.jpg") . '" alt="' . $empresa->nombre . '">
                </td>
            </tr>
        </table>
                  ';
    }

    public function getFooter()
    {
        $empresa = Empresa::getModel();
        return '
                <table width="100%">
                    <tr>
                        <td style="font-style:italic; font-size: 10pt; font-weight:bold;  text-align: center; color: #72533F">' . $empresa->linea_uno_pie . '</td>
                    </tr>
                    <tr>
                        <td style="font-size: 7.5pt; font-weight:bold; text-align: center;">' . $empresa->linea_dos_pie . '</td>
                    </tr>
                </table>
            ';
    }

}
