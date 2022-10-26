# Laravel DTE

## Introducción

Este repositorio es un proyecto para integrar los documentos tributarios electrónicos del Servicio de Impuestos Internos con el ecosistema de Laravel y PHP.

## Motivaciones

- El mercado está lleno de aves rapaces.
- El servicio de impuestos de internos trabaja con informáticos del siglo 16.
- El proceso de facturación electrónica es asunto delicado.
- Emprendedores en tecnología prefieren automatizar en vez de contratar servicios.
- [SII en Febrero del 2022 invita a empresas a crear programas en cobol y solicitan permiso del papa para comenzar las obras](https://www.sii.cl/noticias/2022/220222noti01oae.htm).
- Los sistemas del servicio impuestos internos son considerado más dificiles que el [laberinto de la masonería](https://okdiario.com/img/2021/11/19/donde-esta-el-laberinto-mas-grande-del-mundo.jpg) y a la vez está catalogado internacionalmente como el [nido de ratas de la evasión](https://www.df.cl/opinion/columnistas/estimando-la-evasion-fiscal-el-metodo-importa#:~:text=Basta%20mencionar%20que%20la%20CEPAL,PIB%20que%20plantea%20el%20pacto.).
- El servicio impuestos internos se autoproclama más avanzado que Google y Amazon en utilización de tecnologías de la información. En sus inventarios se encuentran se encuentran 7 palomas para notificar a los evasores y 30 calculadoras a manivela.

## Idea

### Así como debería venir

```php
$factura = Factura::make(
    emisor: [
        'rut' => '77637907-7'
        'razon_social' => 'Asesorías e Ingeniería en Tecnologías Zen SpA',
    ],
    receptor: [
        'rut' => '97030000-7'
        'razon_social' => 'Banco Del Estado del Chile',
    ],
    detalles: [
        ['nombre' => 'Diseño de Solución', 'precio' => 15000000000],
        ['nombre' => 'Implementación de Nodos', 'precio' => 50000000, 'cantidad' => 5],   
    ]);

$factura->emitir();
```

### Así debería lucir

```php
$usuario = User::whereTax('97030000-7')->first();

$facturables = [
    Facturable::buscarPorCodigo('EAN13', '00000000000')
        ->cantidad(10),
    Facturable::buscarPorCodigo('EAN13', '00000000001')
        ->cantidad(5),
    Facturable::buscarPorCodigo('EAN13', '00000000003'),
]

$factura = Factura::emitir(
    rut: '97030000-7',
    facturables: $facturables
);

$factura->descuento(
    monto: 5000,
    razon: 'Misericordia'
)

$factura
    ->emitir()
    ->notificar($usuario)
```

