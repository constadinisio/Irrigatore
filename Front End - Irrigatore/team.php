<!DOCTYPE html>
<html lang="en">
  <head>
    <title>¿Quienes Somos?</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"
      rel="stylesheet"
    />

    <!-- The website JavaScript file -->
    <script src="/script.js" defer></script>
  </head>
  <body class="bg-gray-100" style="overflow: hidden;">
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
      <div class="container mx-auto py-4">
        <h1 class="text-4xl font-bold text-center text-orange-500 my-4 mb-6">
          ¡Nuestro Equipo 🤜🤛!
        </h1>
        <div class="grid grid-cols-3 grid-rows-2 gap-4">
          <div class="bg-white rounded-lg shadow-lg p-6 text-center">
            <img alt="Portrait of Alex Smith" class="w-24 h-24 rounded-full mx-auto mb-4" style="height:125px" src="https://irrigatore.s3.us-east-2.amazonaws.com/foto-cdinisio.jpg?response-content-disposition=inline&X-Amz-Content-Sha256=UNSIGNED-PAYLOAD&X-Amz-Security-Token=IQoJb3JpZ2luX2VjEC4aCXVzLWVhc3QtMiJGMEQCIDHNmFVFD%2BZnTpifmS%2FOdm7uecoUVz04YUBntR9GIUUPAiBFoHeH3AXc1OgDjqNBYvubF1K2hOuqWCjIegdIJAGkYCrQAwin%2F%2F%2F%2F%2F%2F%2F%2F%2F%2F8BEAAaDDkxMzUyNDk0MDYyOCIMYfxWBb3MGMjdGMmsKqQDMq0H49FZmLZPZPA0g7YhzP7erbH5bEnu%2BQHVxtqQ6naxB8I79Vvfz4tlDOsy%2Fwub9J4siG3CFHpsE8wrZE4%2F7UXaaihn4pDv58hZddceAyds1ZHO0YHlQ44BOAwwSWjuCW61biCeXT70EjfQlMo3Y2upbO2NNZ85uZCa%2BRFmQgoZVeGmoq6xRXt1FM6Qx6rAZnSOSg7VMWUmwDu8X8NMXyHTxbjsjcZAVB3%2BKhHlSn5zq5E2MWlC1puXaBzhsjA1CA%2BdUkoRfdYGYOkg%2FrUxO45NL9hITvF%2F2sSA89qvI98Ybm2V4D4vL0lxFgEicf%2BadhL%2FOwE3jea%2FB5XNBe%2Fjbu%2B1NQFs%2FCcHu9yP6tFB%2Bto%2FrdujrXZlsPDL38hDUBtapLnYlZjwsy9weCNmJZsRxE%2BQL3CANS3JNLdxJ5opH3vw%2BL%2BtSA8SJSgACk58lmQES62y4RAthhwved0%2BzZiGQYPnk%2BFhrhdPuYhujBNaII8thKroPl71T%2BQgpdCFyEekqs6%2BRsCerWMnKXPfzI6Ts4dmGsbkXZ2xL4vBTrkm2XZki2cPMLe8k7kGOuUCPlPuAaQSYWMMPdp%2BxyzH%2BGBLAXPasfQL5ErIOkxoLbuK7aZf%2BbsAQKbhlghEJ00dNNxgG%2FqIpgg97FUj8VOVAo1NKHzp%2BXUOTOBkh%2FqH1KFVqKywlU1ILT%2FkPn3JsFp%2BjLHYFTlEpHkY2nVozGcSqpSm7UDfd46BIHjR%2Bpx6cbLcQIco%2B5V%2FHS9Ps7iopcScyULj0zKFCxVACsT%2BOYlnjPJCEvbgP%2BudVVjG4M%2FQqUm4wO9EIq0I8zwF%2BZtNWpXndXD1Y2dRfC%2Bo2jxED8Vt0W6hqQ%2FlKvfCfoipkMJq77SYfa9UmOq8ynwKQFZqmyXC6pF0C%2FnAFFn6rH7Gs1Qoubitfg5mk0qKTsJqNQQhpI1IzqKYftQwbMhiePGnklz0NKiZtPL00YSRD%2BhxRw05P%2FcPHT1CNYK3IZRDd1hgAWqD8Ngsz0VCwjcHxJYYYe9DkDwASfWd0EsVZKdMLc55A%2BUbyV7%2B&X-Amz-Algorithm=AWS4-HMAC-SHA256&X-Amz-Credential=ASIA5JMSUL5KEQIC6RZB%2F20241101%2Fus-east-2%2Fs3%2Faws4_request&X-Amz-Date=20241101T135849Z&X-Amz-Expires=43200&X-Amz-SignedHeaders=host&X-Amz-Signature=617f3434769991bce8d3faf859fb4ef9235acf5ed00f9fbbd55ff562a1b9c76f">
            <h2 class="text-xl font-bold mb-2">Constantino Di Nisio</h2>
            <p class="text-gray-600 mb-4">
              Hola, soy Constantino Di Nisio y actualmente estoy en un emocionante viaje en el mundo de la programación. Me especializo en HTML, JavaScript y C++, tres lenguajes que me están permitiendo construir desde interfaces web interactivas hasta soluciones de software eficientes. 
            </p>
            <br>
            <p class="font-bold text-gray-800 mb-4">Programación - HTML y Arduino</p>
          </div>
          <div class="bg-white rounded-lg shadow-lg p-6 text-center">
          <img alt="Portrait of Alex Smith" class="w-24 h-24 rounded-full mx-auto mb-4" style="height:125px" src="https://irrigatore.s3.us-east-2.amazonaws.com/foto-mbaez.jpg?response-content-disposition=inline&X-Amz-Content-Sha256=UNSIGNED-PAYLOAD&X-Amz-Security-Token=IQoJb3JpZ2luX2VjEC4aCXVzLWVhc3QtMiJGMEQCIDHNmFVFD%2BZnTpifmS%2FOdm7uecoUVz04YUBntR9GIUUPAiBFoHeH3AXc1OgDjqNBYvubF1K2hOuqWCjIegdIJAGkYCrQAwin%2F%2F%2F%2F%2F%2F%2F%2F%2F%2F8BEAAaDDkxMzUyNDk0MDYyOCIMYfxWBb3MGMjdGMmsKqQDMq0H49FZmLZPZPA0g7YhzP7erbH5bEnu%2BQHVxtqQ6naxB8I79Vvfz4tlDOsy%2Fwub9J4siG3CFHpsE8wrZE4%2F7UXaaihn4pDv58hZddceAyds1ZHO0YHlQ44BOAwwSWjuCW61biCeXT70EjfQlMo3Y2upbO2NNZ85uZCa%2BRFmQgoZVeGmoq6xRXt1FM6Qx6rAZnSOSg7VMWUmwDu8X8NMXyHTxbjsjcZAVB3%2BKhHlSn5zq5E2MWlC1puXaBzhsjA1CA%2BdUkoRfdYGYOkg%2FrUxO45NL9hITvF%2F2sSA89qvI98Ybm2V4D4vL0lxFgEicf%2BadhL%2FOwE3jea%2FB5XNBe%2Fjbu%2B1NQFs%2FCcHu9yP6tFB%2Bto%2FrdujrXZlsPDL38hDUBtapLnYlZjwsy9weCNmJZsRxE%2BQL3CANS3JNLdxJ5opH3vw%2BL%2BtSA8SJSgACk58lmQES62y4RAthhwved0%2BzZiGQYPnk%2BFhrhdPuYhujBNaII8thKroPl71T%2BQgpdCFyEekqs6%2BRsCerWMnKXPfzI6Ts4dmGsbkXZ2xL4vBTrkm2XZki2cPMLe8k7kGOuUCPlPuAaQSYWMMPdp%2BxyzH%2BGBLAXPasfQL5ErIOkxoLbuK7aZf%2BbsAQKbhlghEJ00dNNxgG%2FqIpgg97FUj8VOVAo1NKHzp%2BXUOTOBkh%2FqH1KFVqKywlU1ILT%2FkPn3JsFp%2BjLHYFTlEpHkY2nVozGcSqpSm7UDfd46BIHjR%2Bpx6cbLcQIco%2B5V%2FHS9Ps7iopcScyULj0zKFCxVACsT%2BOYlnjPJCEvbgP%2BudVVjG4M%2FQqUm4wO9EIq0I8zwF%2BZtNWpXndXD1Y2dRfC%2Bo2jxED8Vt0W6hqQ%2FlKvfCfoipkMJq77SYfa9UmOq8ynwKQFZqmyXC6pF0C%2FnAFFn6rH7Gs1Qoubitfg5mk0qKTsJqNQQhpI1IzqKYftQwbMhiePGnklz0NKiZtPL00YSRD%2BhxRw05P%2FcPHT1CNYK3IZRDd1hgAWqD8Ngsz0VCwjcHxJYYYe9DkDwASfWd0EsVZKdMLc55A%2BUbyV7%2B&X-Amz-Algorithm=AWS4-HMAC-SHA256&X-Amz-Credential=ASIA5JMSUL5KEQIC6RZB%2F20241101%2Fus-east-2%2Fs3%2Faws4_request&X-Amz-Date=20241101T140000Z&X-Amz-Expires=43200&X-Amz-SignedHeaders=host&X-Amz-Signature=8833c8aae80b55654f51e91a14b2cde6d6f60e39d0be38f5dfa0ce969370274a">
            <h2 class="text-xl font-bold mb-2">Marcos Baez</h2>
            <p class="text-gray-600 mb-4">
              Hola, soy Marcos Baez y actualmente me desempeño como Ayudante en Código e Ideador de Software en un proyecto en el que estamos construyendo soluciones innovadoras. En este rol, colaboro en el desarrollo y perfeccionamiento del código.
            </p>
            <br>
            <br>
            <p class="font-bold text-gray-800 mb-4">Ayudante en Código e Ideador de Software</p>
          </div>
          <div class="bg-white rounded-lg shadow-lg p-6 text-center">
            <img alt="Portrait of May Brown" class="w-24 h-24 rounded-full mx-auto mb-4" style="height:125px" src="https://irrigatore.s3.us-east-2.amazonaws.com/foto-lmazza.jpg?response-content-disposition=inline&X-Amz-Content-Sha256=UNSIGNED-PAYLOAD&X-Amz-Security-Token=IQoJb3JpZ2luX2VjEC4aCXVzLWVhc3QtMiJGMEQCIDHNmFVFD%2BZnTpifmS%2FOdm7uecoUVz04YUBntR9GIUUPAiBFoHeH3AXc1OgDjqNBYvubF1K2hOuqWCjIegdIJAGkYCrQAwin%2F%2F%2F%2F%2F%2F%2F%2F%2F%2F8BEAAaDDkxMzUyNDk0MDYyOCIMYfxWBb3MGMjdGMmsKqQDMq0H49FZmLZPZPA0g7YhzP7erbH5bEnu%2BQHVxtqQ6naxB8I79Vvfz4tlDOsy%2Fwub9J4siG3CFHpsE8wrZE4%2F7UXaaihn4pDv58hZddceAyds1ZHO0YHlQ44BOAwwSWjuCW61biCeXT70EjfQlMo3Y2upbO2NNZ85uZCa%2BRFmQgoZVeGmoq6xRXt1FM6Qx6rAZnSOSg7VMWUmwDu8X8NMXyHTxbjsjcZAVB3%2BKhHlSn5zq5E2MWlC1puXaBzhsjA1CA%2BdUkoRfdYGYOkg%2FrUxO45NL9hITvF%2F2sSA89qvI98Ybm2V4D4vL0lxFgEicf%2BadhL%2FOwE3jea%2FB5XNBe%2Fjbu%2B1NQFs%2FCcHu9yP6tFB%2Bto%2FrdujrXZlsPDL38hDUBtapLnYlZjwsy9weCNmJZsRxE%2BQL3CANS3JNLdxJ5opH3vw%2BL%2BtSA8SJSgACk58lmQES62y4RAthhwved0%2BzZiGQYPnk%2BFhrhdPuYhujBNaII8thKroPl71T%2BQgpdCFyEekqs6%2BRsCerWMnKXPfzI6Ts4dmGsbkXZ2xL4vBTrkm2XZki2cPMLe8k7kGOuUCPlPuAaQSYWMMPdp%2BxyzH%2BGBLAXPasfQL5ErIOkxoLbuK7aZf%2BbsAQKbhlghEJ00dNNxgG%2FqIpgg97FUj8VOVAo1NKHzp%2BXUOTOBkh%2FqH1KFVqKywlU1ILT%2FkPn3JsFp%2BjLHYFTlEpHkY2nVozGcSqpSm7UDfd46BIHjR%2Bpx6cbLcQIco%2B5V%2FHS9Ps7iopcScyULj0zKFCxVACsT%2BOYlnjPJCEvbgP%2BudVVjG4M%2FQqUm4wO9EIq0I8zwF%2BZtNWpXndXD1Y2dRfC%2Bo2jxED8Vt0W6hqQ%2FlKvfCfoipkMJq77SYfa9UmOq8ynwKQFZqmyXC6pF0C%2FnAFFn6rH7Gs1Qoubitfg5mk0qKTsJqNQQhpI1IzqKYftQwbMhiePGnklz0NKiZtPL00YSRD%2BhxRw05P%2FcPHT1CNYK3IZRDd1hgAWqD8Ngsz0VCwjcHxJYYYe9DkDwASfWd0EsVZKdMLc55A%2BUbyV7%2B&X-Amz-Algorithm=AWS4-HMAC-SHA256&X-Amz-Credential=ASIA5JMSUL5KEQIC6RZB%2F20241101%2Fus-east-2%2Fs3%2Faws4_request&X-Amz-Date=20241101T135944Z&X-Amz-Expires=43200&X-Amz-SignedHeaders=host&X-Amz-Signature=d04d32c38a0cb3e609f7bd708a79a245e6e8b6555debe36521f19a5978fdc3d5">
            <h2 class="text-xl font-bold mb-2">Lionel Mazza</h2>
            <p class="text-gray-600 mb-4">
              Hola, soy Lionel Mazza y actualmente tengo el rol de Ideador de Proyecto, un puesto que me permite combinar mi pasión por la tecnología con mi interés en la planificación. Estoy enfocado en el estudio de la programación, con un énfasis especial en redes.
            </p>
            <br>
            <br>
            <p class="font-bold text-gray-800 mb-4">Ideador del Proyecto</p>
          </div>
          <div class="bg-white rounded-lg shadow-lg p-6 text-center">
            <img alt="Portrait of Ann Richmond" class="w-24 h-24 rounded-full mx-auto mb-4" style="height:125px" src="https://irrigatore.s3.us-east-2.amazonaws.com/foto-sbusta.jpg?response-content-disposition=inline&X-Amz-Content-Sha256=UNSIGNED-PAYLOAD&X-Amz-Security-Token=IQoJb3JpZ2luX2VjEC4aCXVzLWVhc3QtMiJGMEQCIDHNmFVFD%2BZnTpifmS%2FOdm7uecoUVz04YUBntR9GIUUPAiBFoHeH3AXc1OgDjqNBYvubF1K2hOuqWCjIegdIJAGkYCrQAwin%2F%2F%2F%2F%2F%2F%2F%2F%2F%2F8BEAAaDDkxMzUyNDk0MDYyOCIMYfxWBb3MGMjdGMmsKqQDMq0H49FZmLZPZPA0g7YhzP7erbH5bEnu%2BQHVxtqQ6naxB8I79Vvfz4tlDOsy%2Fwub9J4siG3CFHpsE8wrZE4%2F7UXaaihn4pDv58hZddceAyds1ZHO0YHlQ44BOAwwSWjuCW61biCeXT70EjfQlMo3Y2upbO2NNZ85uZCa%2BRFmQgoZVeGmoq6xRXt1FM6Qx6rAZnSOSg7VMWUmwDu8X8NMXyHTxbjsjcZAVB3%2BKhHlSn5zq5E2MWlC1puXaBzhsjA1CA%2BdUkoRfdYGYOkg%2FrUxO45NL9hITvF%2F2sSA89qvI98Ybm2V4D4vL0lxFgEicf%2BadhL%2FOwE3jea%2FB5XNBe%2Fjbu%2B1NQFs%2FCcHu9yP6tFB%2Bto%2FrdujrXZlsPDL38hDUBtapLnYlZjwsy9weCNmJZsRxE%2BQL3CANS3JNLdxJ5opH3vw%2BL%2BtSA8SJSgACk58lmQES62y4RAthhwved0%2BzZiGQYPnk%2BFhrhdPuYhujBNaII8thKroPl71T%2BQgpdCFyEekqs6%2BRsCerWMnKXPfzI6Ts4dmGsbkXZ2xL4vBTrkm2XZki2cPMLe8k7kGOuUCPlPuAaQSYWMMPdp%2BxyzH%2BGBLAXPasfQL5ErIOkxoLbuK7aZf%2BbsAQKbhlghEJ00dNNxgG%2FqIpgg97FUj8VOVAo1NKHzp%2BXUOTOBkh%2FqH1KFVqKywlU1ILT%2FkPn3JsFp%2BjLHYFTlEpHkY2nVozGcSqpSm7UDfd46BIHjR%2Bpx6cbLcQIco%2B5V%2FHS9Ps7iopcScyULj0zKFCxVACsT%2BOYlnjPJCEvbgP%2BudVVjG4M%2FQqUm4wO9EIq0I8zwF%2BZtNWpXndXD1Y2dRfC%2Bo2jxED8Vt0W6hqQ%2FlKvfCfoipkMJq77SYfa9UmOq8ynwKQFZqmyXC6pF0C%2FnAFFn6rH7Gs1Qoubitfg5mk0qKTsJqNQQhpI1IzqKYftQwbMhiePGnklz0NKiZtPL00YSRD%2BhxRw05P%2FcPHT1CNYK3IZRDd1hgAWqD8Ngsz0VCwjcHxJYYYe9DkDwASfWd0EsVZKdMLc55A%2BUbyV7%2B&X-Amz-Algorithm=AWS4-HMAC-SHA256&X-Amz-Credential=ASIA5JMSUL5KEQIC6RZB%2F20241101%2Fus-east-2%2Fs3%2Faws4_request&X-Amz-Date=20241101T140037Z&X-Amz-Expires=43200&X-Amz-SignedHeaders=host&X-Amz-Signature=ff0f4e4e67377f1140398d9184e34f241c9a0db75bf8547d08f96c6dc5fb5770">
            <h2 class="text-xl font-bold mb-2">Santino Bustamante</h2>
            <p class="text-gray-600 mb-4">
              Hola, soy Santino Bustamante y actualmente trabajo como Documentador en una empresa nacional. En mi rol, me encargo de crear y mantener la documentación técnica y funcional de los proyectos.
            </p>
            <br>
            <p class="font-bold text-gray-800 mb-4">Documentador</p>
          </div>
          <div class="bg-white rounded-lg shadow-lg p-6 text-center">
            <img alt="Portrait of Roxie Swanson" class="w-24 h-24 rounded-full mx-auto mb-4" style="height:125px" src="https://irrigatore.s3.us-east-2.amazonaws.com/foto-lbernat.jpg?response-content-disposition=inline&X-Amz-Content-Sha256=UNSIGNED-PAYLOAD&X-Amz-Security-Token=IQoJb3JpZ2luX2VjEC4aCXVzLWVhc3QtMiJGMEQCIDHNmFVFD%2BZnTpifmS%2FOdm7uecoUVz04YUBntR9GIUUPAiBFoHeH3AXc1OgDjqNBYvubF1K2hOuqWCjIegdIJAGkYCrQAwin%2F%2F%2F%2F%2F%2F%2F%2F%2F%2F8BEAAaDDkxMzUyNDk0MDYyOCIMYfxWBb3MGMjdGMmsKqQDMq0H49FZmLZPZPA0g7YhzP7erbH5bEnu%2BQHVxtqQ6naxB8I79Vvfz4tlDOsy%2Fwub9J4siG3CFHpsE8wrZE4%2F7UXaaihn4pDv58hZddceAyds1ZHO0YHlQ44BOAwwSWjuCW61biCeXT70EjfQlMo3Y2upbO2NNZ85uZCa%2BRFmQgoZVeGmoq6xRXt1FM6Qx6rAZnSOSg7VMWUmwDu8X8NMXyHTxbjsjcZAVB3%2BKhHlSn5zq5E2MWlC1puXaBzhsjA1CA%2BdUkoRfdYGYOkg%2FrUxO45NL9hITvF%2F2sSA89qvI98Ybm2V4D4vL0lxFgEicf%2BadhL%2FOwE3jea%2FB5XNBe%2Fjbu%2B1NQFs%2FCcHu9yP6tFB%2Bto%2FrdujrXZlsPDL38hDUBtapLnYlZjwsy9weCNmJZsRxE%2BQL3CANS3JNLdxJ5opH3vw%2BL%2BtSA8SJSgACk58lmQES62y4RAthhwved0%2BzZiGQYPnk%2BFhrhdPuYhujBNaII8thKroPl71T%2BQgpdCFyEekqs6%2BRsCerWMnKXPfzI6Ts4dmGsbkXZ2xL4vBTrkm2XZki2cPMLe8k7kGOuUCPlPuAaQSYWMMPdp%2BxyzH%2BGBLAXPasfQL5ErIOkxoLbuK7aZf%2BbsAQKbhlghEJ00dNNxgG%2FqIpgg97FUj8VOVAo1NKHzp%2BXUOTOBkh%2FqH1KFVqKywlU1ILT%2FkPn3JsFp%2BjLHYFTlEpHkY2nVozGcSqpSm7UDfd46BIHjR%2Bpx6cbLcQIco%2B5V%2FHS9Ps7iopcScyULj0zKFCxVACsT%2BOYlnjPJCEvbgP%2BudVVjG4M%2FQqUm4wO9EIq0I8zwF%2BZtNWpXndXD1Y2dRfC%2Bo2jxED8Vt0W6hqQ%2FlKvfCfoipkMJq77SYfa9UmOq8ynwKQFZqmyXC6pF0C%2FnAFFn6rH7Gs1Qoubitfg5mk0qKTsJqNQQhpI1IzqKYftQwbMhiePGnklz0NKiZtPL00YSRD%2BhxRw05P%2FcPHT1CNYK3IZRDd1hgAWqD8Ngsz0VCwjcHxJYYYe9DkDwASfWd0EsVZKdMLc55A%2BUbyV7%2B&X-Amz-Algorithm=AWS4-HMAC-SHA256&X-Amz-Credential=ASIA5JMSUL5KEQIC6RZB%2F20241101%2Fus-east-2%2Fs3%2Faws4_request&X-Amz-Date=20241101T135925Z&X-Amz-Expires=43200&X-Amz-SignedHeaders=host&X-Amz-Signature=44770e4d9545d0c3794c14a436416369721b444fed5d041e2aee8593562e71a2">
            <h2 class="text-xl font-bold mb-2">Laureano Bernat</h2>
            <p class="text-gray-600 mb-4">
              Hola, soy Laureano Bernat y me desempeño como Documentador en una empresa nacional. Mi rol se encarga de gestionar la documentación técnica, garantizando que cada proceso esté detallado de manera clara y accesible para todos los miembros del equipo.
            </p>
            <p class="font-bold text-gray-800 mb-4">Documentador</p>
          </div>
          <div class="bg-white rounded-lg shadow-lg p-6 text-center">
            <img alt="Portrait of Roxie Swanson" class="w-24 h-24 rounded-full mx-auto mb-4" style="height:125px" src="https://irrigatore.s3.us-east-2.amazonaws.com/foto-mferro.jpg?response-content-disposition=inline&X-Amz-Content-Sha256=UNSIGNED-PAYLOAD&X-Amz-Security-Token=IQoJb3JpZ2luX2VjEC4aCXVzLWVhc3QtMiJGMEQCIDHNmFVFD%2BZnTpifmS%2FOdm7uecoUVz04YUBntR9GIUUPAiBFoHeH3AXc1OgDjqNBYvubF1K2hOuqWCjIegdIJAGkYCrQAwin%2F%2F%2F%2F%2F%2F%2F%2F%2F%2F8BEAAaDDkxMzUyNDk0MDYyOCIMYfxWBb3MGMjdGMmsKqQDMq0H49FZmLZPZPA0g7YhzP7erbH5bEnu%2BQHVxtqQ6naxB8I79Vvfz4tlDOsy%2Fwub9J4siG3CFHpsE8wrZE4%2F7UXaaihn4pDv58hZddceAyds1ZHO0YHlQ44BOAwwSWjuCW61biCeXT70EjfQlMo3Y2upbO2NNZ85uZCa%2BRFmQgoZVeGmoq6xRXt1FM6Qx6rAZnSOSg7VMWUmwDu8X8NMXyHTxbjsjcZAVB3%2BKhHlSn5zq5E2MWlC1puXaBzhsjA1CA%2BdUkoRfdYGYOkg%2FrUxO45NL9hITvF%2F2sSA89qvI98Ybm2V4D4vL0lxFgEicf%2BadhL%2FOwE3jea%2FB5XNBe%2Fjbu%2B1NQFs%2FCcHu9yP6tFB%2Bto%2FrdujrXZlsPDL38hDUBtapLnYlZjwsy9weCNmJZsRxE%2BQL3CANS3JNLdxJ5opH3vw%2BL%2BtSA8SJSgACk58lmQES62y4RAthhwved0%2BzZiGQYPnk%2BFhrhdPuYhujBNaII8thKroPl71T%2BQgpdCFyEekqs6%2BRsCerWMnKXPfzI6Ts4dmGsbkXZ2xL4vBTrkm2XZki2cPMLe8k7kGOuUCPlPuAaQSYWMMPdp%2BxyzH%2BGBLAXPasfQL5ErIOkxoLbuK7aZf%2BbsAQKbhlghEJ00dNNxgG%2FqIpgg97FUj8VOVAo1NKHzp%2BXUOTOBkh%2FqH1KFVqKywlU1ILT%2FkPn3JsFp%2BjLHYFTlEpHkY2nVozGcSqpSm7UDfd46BIHjR%2Bpx6cbLcQIco%2B5V%2FHS9Ps7iopcScyULj0zKFCxVACsT%2BOYlnjPJCEvbgP%2BudVVjG4M%2FQqUm4wO9EIq0I8zwF%2BZtNWpXndXD1Y2dRfC%2Bo2jxED8Vt0W6hqQ%2FlKvfCfoipkMJq77SYfa9UmOq8ynwKQFZqmyXC6pF0C%2FnAFFn6rH7Gs1Qoubitfg5mk0qKTsJqNQQhpI1IzqKYftQwbMhiePGnklz0NKiZtPL00YSRD%2BhxRw05P%2FcPHT1CNYK3IZRDd1hgAWqD8Ngsz0VCwjcHxJYYYe9DkDwASfWd0EsVZKdMLc55A%2BUbyV7%2B&X-Amz-Algorithm=AWS4-HMAC-SHA256&X-Amz-Credential=ASIA5JMSUL5KEQIC6RZB%2F20241101%2Fus-east-2%2Fs3%2Faws4_request&X-Amz-Date=20241101T140021Z&X-Amz-Expires=43200&X-Amz-SignedHeaders=host&X-Amz-Signature=9a3fe42c64c4d25a6b5988e49e4c4d2a7fef15bb135b1c98230c56c677e57cfe">
            <h2 class="text-xl font-bold mb-2">Martin Ferro</h2>
            <p class="text-gray-600 mb-4">
              Hola, soy Martin Ferro y trabajo como programador en una empresa internacional. Actualmente, tengo el rol de tester en un proyecto llamado Irrigatore, donde me encargo de garantizar que cada componente del sistema funcione correctamente y cumpla con los más altos estándares de calidad.
            </p>
            <p class="font-bold text-gray-800 mb-4">Testing</p>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>
