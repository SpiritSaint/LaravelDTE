<?php

namespace SpiritSaint\LaravelDTE\Documents;

use Inspirum\XML\Builder\DefaultDocumentFactory;
use Inspirum\XML\Builder\DefaultDOMDocumentFactory;
use Inspirum\XML\Builder\Document;
use Inspirum\XML\Builder\Node;
use SpiritSaint\LaravelDTE\Repositories\ConfigurationsRepository;

class Base
{
    public static function factory(): DefaultDocumentFactory
    {
        return new DefaultDocumentFactory(new DefaultDOMDocumentFactory());
    }

    public static function createDocument(DefaultDocumentFactory $factory): Document
    {
        return $factory->create(ConfigurationsRepository::$XML_version, ConfigurationsRepository::$encoding);
    }

    public static function createDTE(Node $document): Node
    {
        return $document->addElement('DTE', ['version' => ConfigurationsRepository::$version]);
    }

    public static function createCaratula(Node $document, array $parametros)
    {
        $caratula = $document->addElement('Caratula', ['version' => "1.0"]);
        $caratula->addTextElement('RutEmisor', $parametros['emisor']['rut']);
        $caratula->addTextElement('RutReceptor', $parametros['receptor']['rut']);
        $caratula->addTextElement('FchResol', $parametros['id']['fecha']);
        $caratula->addTextElement('NroResol', 0);
        $caratula->addTextElement('TmstFirmaEnv', '2003-10-13T09:33:22');
        $sub = $caratula->addElement('SubTotDTE');
        $sub->addTextElement('TpoDTE', $parametros['id']['tipo']);
        $sub->addTextElement('NroDTE', $parametros['id']['numero']);
    }

    public static function createDocumento(Node $dte, string $id): Node
    {
        return $dte->addElement('Documento', ['ID' => $id]);
    }

    public static function setIdDoc(Node $encabezado, array $parametros)
    {
        $id_documento = $encabezado->addElement('IdDoc');
        $id_documento->addTextElement('TipoDTE', $parametros['id']['tipo']);
        $id_documento->addTextElement('Folio', $parametros['id']['folio']);
        $id_documento->addTextElement('FchEmis', $parametros['id']['fecha']);
    }

    public static function setEmisor(Node $encabezado, array $parametros)
    {
        $emisor = $encabezado->addElement('Emisor');
        $emisor->addTextElement('RUTEmisor', $parametros['emisor']['rut']);
        $emisor->addTextElement('RznSoc', $parametros['emisor']['razon_social']);
        $emisor->addTextElement('GiroEmis', $parametros['emisor']['giro']);
        $emisor->addTextElement('Acteco', $parametros['emisor']['actividad_economica']);
        $emisor->addTextElement('CdgSIISucur', $parametros['emisor']['codigo_sucursal_sii']);
        $emisor->addTextElement('DirOrigen', $parametros['emisor']['direccion']);
        $emisor->addTextElement('CmnaOrigen', $parametros['emisor']['comuna']);
        $emisor->addTextElement('CiudadOrigen', $parametros['emisor']['ciudad']);
    }

    public static function setReceptor(Node $encabezado, array $parametros)
    {
        $receptor = $encabezado->addElement('Receptor');
        $receptor->addTextElement('RUTRecep', $parametros['receptor']['rut']);
        $receptor->addTextElement('RznSocRecep', $parametros['receptor']['razon_social']);
        $receptor->addTextElement('GiroRecep', $parametros['receptor']['giro']);
        $receptor->addTextElement('DirRecep', $parametros['receptor']['direccion']);
        $receptor->addTextElement('CmnaRecep', $parametros['receptor']['comuna']);
        $receptor->addTextElement('CiudadRecep', $parametros['receptor']['ciudad']);
    }

    public static function setTotales(Node $encabezado, array $parametros)
    {
        $totales = $encabezado->addElement('Totales');
        $totales->addTextElement('MntNeto', $parametros['montos']['neto']);
        $totales->addTextElement('TasaIVA', $parametros['montos']['porcentaje_iva']);
        $totales->addTextElement('IVA', $parametros['montos']['iva']);
        $totales->addTextElement('MntTotal', $parametros['montos']['total']);
    }

    public static function addDetalle(Node $documento, array $valores, int $id)
    {
        $detalle = $documento->addElement('Detalle');
        $detalle->addTextElement('NroLinDet', $id);
        $detalle->addTextElement('NmbItem', $valores['nombre']);
        $detalle->addTextElement('DscItem', $valores['descripcion']);
        $detalle->addTextElement('QtyItem', $valores['cantidad']);
        $detalle->addTextElement('PrcItem', $valores['precio']);
        $detalle->addTextElement('MontoItem', $valores['total']);
    }


    public static function make(array $parametros)
    {
        $factory = static::factory();
        $document = static::createDocument($factory);

        $envio_dte = $document->addElement('EnvioDTE', [
            'xmlns' => 'http://www.sii.cl/SiiDte',
            'xmlns:xsi' => 'http://www.w3.org/2001/XMLSchema-instance',
            'xsi:schemaLocation' => 'http://www.sii.cl/SiiDte EnvioDTE_v10.xsd',
            'version' => '1.0',
        ]);

        $set_dte = $envio_dte->addElement('SetDTE', ['ID' => 'SetDoc']);

        static::createCaratula($set_dte, $parametros);

        $dte = static::createDTE($set_dte);

        $documento = static::createDocumento(
            $dte,
            "F{$parametros['id']['folio']}T{$parametros['id']['tipo']}"
        );

        $encabezado = $documento->addElement('Encabezado');

        static::setIdDoc($encabezado, $parametros);
        static::setEmisor($encabezado, $parametros);
        static::setReceptor($encabezado, $parametros);
        static::setTotales($encabezado, $parametros);

        foreach ($parametros['detalles'] as $index => $detalle) {
            static::addDetalle($documento, $detalle, $index + 1);
        }

        $document->save('schemas/RESULTADO.xml', true);
        return $document;
    }
}