<!DOCTYPE html>
<html lang="en">

<head>
  <title>Contacto</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />

  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <?php header('Content-Type: text/html; charset=utf-8'); ?>

  <!-- The website JavaScript file -->
  <script src="/script.js" defer></script>
</head>

<body class="bg-gray-100">
  <div class="flex">
    <!-- Sidebar -->
    <div class="w-64 text-white h-screen" style="background-color:#10242b">
            <div class="p-4 flex items-center">
                <img alt="Logo" class="h-20 w-60 mr-2" height="40" src="https://irrigatore.s3.us-east-2.amazonaws.com/Irrigatore.jpg?response-content-disposition=inline&X-Amz-Content-Sha256=UNSIGNED-PAYLOAD&X-Amz-Security-Token=IQoJb3JpZ2luX2VjEC4aCXVzLWVhc3QtMiJGMEQCIDHNmFVFD%2BZnTpifmS%2FOdm7uecoUVz04YUBntR9GIUUPAiBFoHeH3AXc1OgDjqNBYvubF1K2hOuqWCjIegdIJAGkYCrQAwin%2F%2F%2F%2F%2F%2F%2F%2F%2F%2F8BEAAaDDkxMzUyNDk0MDYyOCIMYfxWBb3MGMjdGMmsKqQDMq0H49FZmLZPZPA0g7YhzP7erbH5bEnu%2BQHVxtqQ6naxB8I79Vvfz4tlDOsy%2Fwub9J4siG3CFHpsE8wrZE4%2F7UXaaihn4pDv58hZddceAyds1ZHO0YHlQ44BOAwwSWjuCW61biCeXT70EjfQlMo3Y2upbO2NNZ85uZCa%2BRFmQgoZVeGmoq6xRXt1FM6Qx6rAZnSOSg7VMWUmwDu8X8NMXyHTxbjsjcZAVB3%2BKhHlSn5zq5E2MWlC1puXaBzhsjA1CA%2BdUkoRfdYGYOkg%2FrUxO45NL9hITvF%2F2sSA89qvI98Ybm2V4D4vL0lxFgEicf%2BadhL%2FOwE3jea%2FB5XNBe%2Fjbu%2B1NQFs%2FCcHu9yP6tFB%2Bto%2FrdujrXZlsPDL38hDUBtapLnYlZjwsy9weCNmJZsRxE%2BQL3CANS3JNLdxJ5opH3vw%2BL%2BtSA8SJSgACk58lmQES62y4RAthhwved0%2BzZiGQYPnk%2BFhrhdPuYhujBNaII8thKroPl71T%2BQgpdCFyEekqs6%2BRsCerWMnKXPfzI6Ts4dmGsbkXZ2xL4vBTrkm2XZki2cPMLe8k7kGOuUCPlPuAaQSYWMMPdp%2BxyzH%2BGBLAXPasfQL5ErIOkxoLbuK7aZf%2BbsAQKbhlghEJ00dNNxgG%2FqIpgg97FUj8VOVAo1NKHzp%2BXUOTOBkh%2FqH1KFVqKywlU1ILT%2FkPn3JsFp%2BjLHYFTlEpHkY2nVozGcSqpSm7UDfd46BIHjR%2Bpx6cbLcQIco%2B5V%2FHS9Ps7iopcScyULj0zKFCxVACsT%2BOYlnjPJCEvbgP%2BudVVjG4M%2FQqUm4wO9EIq0I8zwF%2BZtNWpXndXD1Y2dRfC%2Bo2jxED8Vt0W6hqQ%2FlKvfCfoipkMJq77SYfa9UmOq8ynwKQFZqmyXC6pF0C%2FnAFFn6rH7Gs1Qoubitfg5mk0qKTsJqNQQhpI1IzqKYftQwbMhiePGnklz0NKiZtPL00YSRD%2BhxRw05P%2FcPHT1CNYK3IZRDd1hgAWqD8Ngsz0VCwjcHxJYYYe9DkDwASfWd0EsVZKdMLc55A%2BUbyV7%2B&X-Amz-Algorithm=AWS4-HMAC-SHA256&X-Amz-Credential=ASIA5JMSUL5KEQIC6RZB%2F20241101%2Fus-east-2%2Fs3%2Faws4_request&X-Amz-Date=20241101T140053Z&X-Amz-Expires=43200&X-Amz-SignedHeaders=host&X-Amz-Signature=42f30667833fc6bac9e2b2f7df9630fcbc0934cd3af9e2f6187862f228a38897" width="40">
            </div>
            <nav class="mt-10">
                <a class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 mb-4" href="./dashboard.php">
                    <i class="fas fa-home mr-2"></i>
                    Dashboard
                </a>
                <div class="my-2 opacity-75">
                    <p class="text-center text-sm">DATA</p>
                    <div class="border-t border-gray-600 mt-1"></div>
                </div>
                <a class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700" href="./recomendacion.php">
                    <i class="fas fa-plus mr-2"></i>
                    Recomendaciones
                </a>
                <a class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700" href="./team.php">
                    <i class="fas fa-users mr-2"></i>
                    ¿Quienes somos?
                </a>
                <a class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700" href="./documentation.php">
                    <i class="fas fa-book mr-2"></i>
                    Documentación
                </a>
                <a class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700" href="./contacto.php">
                    <i class="fas fa-phone mr-2"></i>
                    Contacto
                </a>
            </nav>
        </div>

    <!-- Main Content -->
    <div class="max-w-6xl mx-auto py-16 px-4">
      <h1 class="text-3xl font-bold text-center mb-8 text-orange-500">
        ¡Contactanos!
      </h1>
      <div class="flex flex-col md:flex-row md:space-x-8">
        <div class="md:w-1/2 space-y-4">
          <p>
          ¿Tiene alguna pregunta? Estamos aquí para ayudarte. A nuestro amable y experto equipo de atención al cliente le gusta Irrigatore y queremos asegurarnos de que a ti también. No dude en ponerse en contacto con nosotros por teléfono, correo electrónico o el siguiente formulario. También puede consultar nuestra página de preguntas frecuentes para obtener respuestas rápidas a las preguntas más frecuentes.
          </p>
          <p>
            <i class="fas fa-map-marker-alt">
            </i>
             Escuela Técnica 20 D.E. 20, Mataderos, CABA
          </p>
          <p>
            <i class="fas fa-phone-alt">
            </i>
           +54 (011) 2475 - 4968
          </p>
          <p>
            <strong>
              Preguntas Generales:
            </strong>
            contact@irrigatore.com
          </p>
          <p>
            <strong>
              Marketing:
            </strong>
            marketing@irrigatore.com
          </p>
          <p>
            <strong>
              Ventas:
            </strong>
            wholesale@irrigatore.com
          </p>
          <p>
            <strong>
              Experiencia del Cliente:
            </strong>
            customerexperience@irrigatore.com
          </p>
        </div>
        <div class="md:w-1/2">
          <form class="space-y-4">
            <div class="flex space-x-4">
              <input class="w-1/2 p-2 border border-gray-300" placeholder="Nombre *" type="text" />
              <input class="w-1/2 p-2 border border-gray-300" placeholder="Apellido *" type="text" />
            </div>
            <input class="w-full p-2 border border-gray-300" placeholder="Email *" type="email" />
            <select class="w-full p-2 border border-gray-300">
              <option>
                Preguntas Generales *
              </option>
              <option>
                Marketing *
              </option>
              <option>
                Ventas °
              </option>
              <option>
                Experiencia del Cliente *
              </option>
            </select>
            <input class="w-full p-2 border border-gray-300" placeholder="Sujeto *" type="text" />
            <textarea class="w-full p-2 border border-gray-300 h-32" placeholder="Mensaje *"></textarea>
            <button class="bg-yellow-500 text-white px-4 py-2" type="submit">
              ENVIAR
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>
</body>

</html>