<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        .card {
            
            width: 400px;
            height: auto;
            background-color: #cee4e2;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            padding: 10px;    
            padding-bottom: 20px;        
            position: absolute;
            top: 20%;
            left: 50%;
            transform: translate(-50%, -50%);
        }
        .watermark {
          position: fixed; /* Fija la imagen en el fondo */
          bottom: 52%; /* Ajusta la posición vertical al fondo */
          left: 64%; /* Ajusta la posición horizontal al fondo */
          index: -1; /* Coloca la imagen detrás de todo el contenido */
          opacity: 0.2; /* Establece la opacidad de la imagen */
          width: 120px;
          height: auto;
        }
        .watermark_one  {
          position: fixed; /* Fija la imagen en el fondo */
          bottom: 68%; /* Ajusta la posición vertical al fondo */
          left: 50%; /* Ajusta la posición horizontal al fondo */
          index: -1; /* Coloca la imagen detrás de todo el contenido */
          opacity: 0.8; /* Establece la opacidad de la imagen */
          width: 50px;
        }
        .watermark_two {
          position: fixed; /* Fija la imagen en el fondo */
          bottom: 68%; /* Ajusta la posición vertical al fondo */
          left: 85%; /* Ajusta la posición horizontal al fondo */
          index: -1; /* Coloca la imagen detrás de todo el contenido */
          opacity: 0.8; /* Establece la opacidad de la imagen */
          width: 50px;
        }
        .logo {
            width: 100px;
            height: auto;
            z-index: 99999;
        }
        .expiration {
          margin-top: 5px;
          text-align: center;
          font-size: 1em;
        }
        h2,p{
          margin-left: 10px;
        }
        h2 {
          text-align: center;
          font-size: 1em;
        }

        p{
          margin: 0.2;
        }
        span {
          font-weight: bold;
        }

       
    </style>
</head>
<body>
  <?php
  $expirationDate = (new DateTime())->add(new DateInterval('P4M'))->format('d/m/Y');
  ?>
    <div class="card">
      <img src="{{ public_path($player->photoPlayer->photo_c_i) }}" alt="" class="watermark ">

      <img src="{{ public_path($player->team->club->logo_path) }}" alt="" class="watermark_one ">
      <img src="{{ public_path($player->photoPlayer->photo_c_i) }}" alt="" class="watermark_two ">

      <h2>AMFSB</h2>

        <table>
            <tr>
                <td>
                    <img src="{{ public_path($player->photoPlayer->photo_path) }}" alt="Foto de jugador" class="logo">
                    
                </td>                
                <td>
                    <div>
                        <p>
                          <span>Nombre: </span>
                          {{ $player->first_name }} {{ $player->second_name }} {{ $player->last_name }} {{ $player->mother_last_name }}
                        </p>
                        <p class="gender">
                          <span>Equipo: </span>
                          {{ $player->team->name }}
                        </p>
                        <p class="gender">
                          <span>Genero: </span>
                          {{ $player->gender }}
                        </p>
                        <p class="birth_date">
                          <span>F. de Nac.: </span>
                          {{ $player->birth_date }}
                        </p>
                        <p class="birth_date">
                          <span>Edad: </span>
                          {{ \Carbon\Carbon::parse($player->birth_date)->age }}
                        </p>
                        <p class="position">
                          <span>Nro. CI: </span>
                          {{ $player->c_i }}
                        </p>
                        <p class="position">
                          <span>Nacionalidad: </span>
                          {{ $player->nacionality }}
                        </p>
                        <p class="position">
                          <span>Lugar de Nac.: </span>
                          {{ $player->region_birth }} {{ $player->country_birth }}
                        </p>
                        <p class="position">
                          <span>ID: </span>
                          {{ $player->id }}
                        </p>
                        <p class="position">
                          <span>Estado: </span>
                          {{ $player->state == 1 ? "Inhabilitado": "Habilitado" }}
                        </p>
                        
                    </div>
                </td>
            </tr>
            <br>
            <tr >              
              <td style="color: #cee4e2">Sello AMFSB</td>
              <td style="text-align: center; color: #cee4e2;">
                <div style="border-bottom: 1.5px solid black; display: inline; padding-bottom: 2px;">
                  línea de Firma deljugador
                </div>
              </td>            
            </tr>
            <tr>
              <td style="text-align: center; border-top: 1.3px solid black;">Sello AMFSB</td>
              <td style="text-align: center ">Firma del Jugador</td>
            </tr>
            
        </table>
      <div>
        <p class="expiration">
          <span>Válido hasta: </span> 
          {{ $expirationDate }}

        </p>      
        
      </div>    
    </div>
</body>
</html>
