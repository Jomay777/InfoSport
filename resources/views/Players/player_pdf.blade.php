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
            background-color: #f2f2f2;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            padding: 10px;    
            padding-bottom: 20px;        
            position: absolute;
            top: 20%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        .logo {
            width: 100px;
            height: auto;
            z-index: 99999;
        }
        h2,p{
          margin-left: 10px;
        }
        h2 {
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
  $expirationDate = (new DateTime())->add(new DateInterval('P3M'))->format('d/m/Y');
  ?>
    <div class="card">
        <table>
            <tr>
                <td>
                    <img src="{{ public_path($player->photoPlayer->photo_path) }}" alt="Company logo" class="logo">
                    
                </td>                
                <td>
                    <div>
                        <h2>
                          <span>Nombre: </span>
                          {{ $player->first_name }} {{ $player->second_name }} {{ $player->last_name }}{{ $player->mother_last_name }}
                        </h2>
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
                          {{ $player->state =1 ? "Inhabilitado": "Habilitado" }}
                        </p>
                        <p class="position">
                          <span>Válido hasta: </span>
                          {{ $expirationDate }}
                        </p>
                    </div>
                </td>
            </tr>
            <br><br>
            <tr >
              
              <td>Sello AMFSB</td>
              <td style="text-align: center">Firma</td>
            </tr>
        </table>
    </div>
</body>
</html>
