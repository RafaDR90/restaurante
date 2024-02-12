<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;



class EmailController extends AbstractController
{
    #[Route('/email')]
    public function sendEmail($htmlContent): Response
    {

        $transport = Transport::fromDsn($_ENV['MAILER_DSN']);
        $mailer = new Mailer($transport);
        $email = (new Email())
            ->from('rafapruebasdaw@gmail.com')
            ->to('lorentain2@gmail.com')
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject('Compra realizada con éxito')
            ->text('Sending emails is fun again!')
            ->html($htmlContent);

        $mailer->send($email);

        // ...
        return new Response('Email enviado');
    }

    public function crearHTML($productos){
        $html = '<!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Productos Comprados</title>
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        margin: 0;
                        padding: 0;
                    }
                    .container {
                        max-width: 800px;
                        margin: 20px auto;
                        padding: 20px;
                        border: 1px solid #ccc;
                        border-radius: 5px;
                    }
                    h1 {
                        text-align: center;
                    }
                    .card {
                        background-color: #f9f9f9;
                        padding: 10px;
                        margin-bottom: 10px;
                        border-radius: 5px;
                    }
                    .card-title {
                        margin: 0;
                        color: #333;
                        font-size: 24px;
                    }
                    .card-text {
                        margin: 5px 0;
                        color: #666;
                        font-size: 16px;
                    }
                    hr {
                        margin: 10px 0;
                        border: none;
                        border-top: 1px solid #ccc;
                    }
                    .total {
                        margin-top: 20px;
                        text-align: right;
                    }
                    
                    .text-totales {
                        font-size: 20px;
                    }
                </style>
            </head>
            <body>
                <h1>Productos comprados</h1>
                <div class="container">';
        $totalProductos = 0;
        $totalPrecio = 0;
        foreach ($productos as $producto) {
            // Muestra producto nombre, producto cantidad
            $totalProductos = $totalProductos + $producto->cantidad;
            $totalPrecio = $totalPrecio + ($producto->getPrecio() * $producto->cantidad);
            $html .=
                '
    <div class="card">
        <h3 class="card-title">' . $producto->getNombre() . '</h3>
        <p class="card-text">Cantidad: ' . $producto->cantidad . '</p>
        <p class="card-text">Peso: ' . $producto->getPeso() . 'kg</p>
        <p class="card-text">Precio: ' . $producto->getPrecio() * $producto->cantidad . '€</p>                                   
    </div>
    <hr>';
        }
        $html .= '<div class="total">
                    <p class="text-totales"><b>Total de productos:</b> '  . $totalProductos . '</p>
                    <p class="text-totales"><b>Precio total:</b> '  . $totalPrecio . '€</p>
                </div> 
                <h1>!Gracias por su compra¡</h1>
            </div>
        </body>
    </html>';
        return $html;
    }

}