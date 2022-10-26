<?php

namespace SpiritSaint\LaravelDTE\Tests\Feature;

use DOMException;
use Inspirum\XML\Builder\DefaultDocumentFactory;
use Inspirum\XML\Builder\DefaultDOMDocumentFactory;
use Inspirum\XML\Reader\DefaultReaderFactory;
use Inspirum\XML\Reader\DefaultXMLReaderFactory;
use SpiritSaint\LaravelDTE\Documents\Base;
use SpiritSaint\LaravelDTE\Tests\TestCase;

class BasicTest extends TestCase
{
    public function testSIIValidation()
    {
        $documentFactory = new DefaultDocumentFactory(new DefaultDOMDocumentFactory());
        $readerFactory = new DefaultReaderFactory(new DefaultXMLReaderFactory(), $documentFactory);
        $document = $readerFactory->create(__DIR__ . '../../../schemas/DTE.xml');
    }

    /**
     * @return void
     * @throws DOMException
     */
    public function testBaseDocument()
    {
        $base = Base::make([
            'id' => [
                'numero' => '1',
                'folio' => '60',
                'tipo' => '33',
                'fecha' => '2003-10-13',
            ],
            'emisor' => [
                'rut' => '97975000-5',
                'razon_social' => 'RUT DE PRUEBA',
                'giro' => 'Insumos de Computacion',
                'actividad_economica' => '31341',
                'codigo_sucursal_sii' => '1234',
                'direccion' => 'Teatinos 120, Piso 4',
                'comuna' => 'Santiago',
                'ciudad' => 'Santiago',
            ],
            'receptor' => [
                'rut' => '77777777-7',
                'razon_social' => 'EMPRESA  LTDA',
                'giro' => 'COMPUTACION',
                'direccion' => 'SAN DIEGO 2222',
                'comuna' => 'LA FLORIDA',
                'ciudad' => 'SANTIAGO',
            ],
            'montos' => [
                'neto' => '100000',
                'porcentaje_iva' => '19',
                'iva' => '19000',
                'total' => '119000',
            ],
            'detalles' => [
                [
                    'nombre' => 'Parlantes Multimedia 180W.',
                    'descripcion' => 'Buena descripción del producto',
                    'cantidad' => 2,
                    'precio' => 50000,
                    'total' => 100000,
                ]
            ]
        ]);

        $base->validate(__DIR__ . '../../../schemas/DTE_v10.xsd');
    }
}