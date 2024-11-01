<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Documentacion</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"
      rel="stylesheet"
    />

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
      <div class="w-full max-w-4xl p-4">
        <iframe class="h-full w-full h-96 border" src="https://drive.google.com/file/d/1I2pcPLcAFps1QpZdbMmGm3O2d80WM4IB/preview" frameborder="0">
        </iframe>
    </div>
    </div>
  </body>
</html>