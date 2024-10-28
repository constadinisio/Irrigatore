<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Recomendaciones</title>
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
                <img alt="Logo" class="h-20 w-60 mr-2" height="40" src="https://irrigatore.s3.us-east-2.amazonaws.com/Irrigatore.jpg?response-content-disposition=inline&amp;X-Amz-Content-Sha256=UNSIGNED-PAYLOAD&amp;X-Amz-Security-Token=IQoJb3JpZ2luX2VjENb%2F%2F%2F%2F%2F%2F%2F%2F%2F%2FwEaCXVzLWVhc3QtMiJHMEUCIAu61wV9WrVsEk8W2RgLkGVrntlcqcTs%2Fx2PViWf9yJ8AiEAwTqh5e%2FpBvZtswe8Jjhvamif8m2j3ur27CRpujRXyyMqxwMIUBAAGgw5MTM1MjQ5NDA2MjgiDD1GVLFrUWMFiKwe5yqkA33hjN%2BeWymnc8ODoZDluisvTS54m46zs3MrUfTd1p2hrBauNxMNC7uE8F%2Ffi5f8mAIuVJM7JoXu4Irr5gbLdgD3lo4D2hZd2FPQM6EyHNZLDtadLLda60nh%2F4vYSXKcMKROoPWLI53UmUGEgkQZLaN76dZcrzK5hg%2FlW%2F0KqYLzZXm4uw2UdeUIhRMVkEL1dpN3dESF2ZmfP%2BajL0XAJT3Qt4gYHMPo15HqMf9k7q0sSi39jdFCkGvsdMqo%2BXL%2FsHXEDbx%2BV58cgu5eB1DLq0U7eu2h6wnBxLCXZX1e%2BSOYTZv1fzfFF5p5moP18Yhn5ow5px3lfJSXLxFVTXOmIxigaELEvVwVy4xAnArGm0MhGhsXVybMlAN1K1ijkZPRoQgIEyg62KaeT29TGIFnA9TB5eyQbO%2FZAGWYa5WTLqAVou5f3dzM2fmyKEpOjMIVh9ibxDF51cIf%2BlZR%2B%2BYUTTXfS%2F1TahZTkwsYKqoWWNL%2Fg5CXvlMTN7IQJqJzoTiWwB6BeVGPnH9si%2BmitHhmUI9E9NikwYsxM8o3o48Z4qXjIpUSEDC9nIC5BjrkAuhydlTENnli4OREQ1O1JmOSwQztcfY8Y3v5B56bWWBWFWTtaTwkyaJKasB1GnsTHF3DwztKuC%2Fj3htVYXhMcszo0eWPsSEkUeHSMip73piBnXwbxZy8fFoDCPWPj2IGveXbPmMsYUXXxnp1T1k745xvkAlLcqP%2BRbdf8WLiKeO%2BWhEDgTg7foXyJJ7ehW7IqCLk50jHNjxVDMTOOPg%2BaIKF%2Fmk7w3Rkirg3%2F3fmRiLt1vuJuF9yeYvHOthrjVQBfQ1swGoDkkO7H%2Fav9co7%2B4NQlV4guKAYAjr5bJsFO2VRVf9pJMMJQM41EpS%2By5n%2B0pB%2FkpDJ3yADIka0U1s0UvMlfPbYp5eHvszr3%2F%2F7ezv8BeMPWtVBDWsbKScGFYONDAVESCts4kq96EAkC6Z%2BBc3lQLtsqR6Dn3TWzQA8DatvjtBJHYi8yO2tk3frPriXqWt%2FRiXg3PGweeiOCKdLR3DLVqWN&amp;X-Amz-Algorithm=AWS4-HMAC-SHA256&amp;X-Amz-Credential=ASIA5JMSUL5KEVHE4YEN%2F20241028%2Fus-east-2%2Fs3%2Faws4_request&amp;X-Amz-Date=20241028T222223Z&amp;X-Amz-Expires=43200&amp;X-Amz-SignedHeaders=host&amp;X-Amz-Signature=733a8a84e29a7d409651c23384aa55a3666f1c8550252c73b30d00d364c7f919" width="40">
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
        <div class="container mx-auto py-12">
            <div class="text-center mb-8 text-black">
                <h2 class="text-4xl font-bold mb-4 text-orange-500">Recomendaciones</h2>
                <p class="text-lg">🌱 Lista de Tips para el Cuidado de tus Plantas 🌺</p>
            </div>
            <div class="grid grid-cols-3 gap-8">
                <div class="bg-blue-700 p-8 rounded-lg text-center text-white">
                    <i class="fas fa-tint text-2xl mb-1"></i>
                    <p class="text-lg font-bold mb-1">¡Cuidado con el Agua!</p>
                    <p>Si no usas nuestro sistema de riego automático tendrás posibilidades muy altas de matar a tus plantas por el exceso de riego.</p>
                </div>
                <div class="bg-blue-700 p-8 rounded-lg text-center text-white">
                    <i class="fas fa-home text-2xl mb-1"></i>
                    <p class="text-lg font-bold mb-1">Lugar Adecuado</p>
                    <p>Tienes que tener en cuenta la cantidad de luz que tus plantas deben recibir. Lo más práctico es colocar las plantas en macetas para facilitar el movimiento y adaptarse mejor a las condiciones climáticas que necesitan.                    </p>
                </div>
                <div class="bg-blue-700 p-8 rounded-lg text-center text-white">
                    <i class="fas fa-search text-2xl mb-1"></i>
                    <p class="text-lg font-bold mb-1">Revisión Regular</p>
                    <p>Una manera de poder corregirlo es si nos preguntamos cómo saber si a mi planta le falta luz o, incluso, atajar la presencia de una plaga que pueda comprometer su vida.</p>
                </div>
                <div class="bg-blue-700 p-8 rounded-lg text-center text-white">
                    <i class="fas fa-sun text-2xl mb-1"></i>
                    <p class="text-lg font-bold mb-1">Adapta a las Plantas al Entorno y Clima</p>
                    <p>Las plantas necesitan un tiempo para acostumbrarse a la luz y a la sombra. No puedes exponer al sol directamente a una planta que ha estado a la sombra, porque si no sus hojas pueden quemarse. Lo mejor es en inviernos acercarlas a la ventana y en invierno alejarlas.</p>
                </div>
                <div class="bg-blue-700 p-8 rounded-lg text-center text-white">
                    <i class="fas fa-shower text-2xl mb-1"></i>
                    <p class="text-lg font-bold mb-1">Limpia tus Plantas</p>
                    <p>Si estas no se mantienen limpias, corren el riesgo de adquirir un aspecto marchito o enfermarse. Con pasar un paño húmedo es suficiente. En el caso de que necesites desinfectar la planta de alguna plaga, también puedes emplear algún jabón insecticida.</p>
                </div>
                <div class="bg-blue-700 p-8 rounded-lg text-center text-white">
                    <i class="fas fa-fire-extinguisher text-2xl mb-1"></i>
                    <p class="text-lg font-bold mb-1">Evita el Calor</p>
                    <p>Aquellas plantas que tengan flores, deben huir del calor. Pues si no se secarán mucho más rápido.</p>
                </div>
            </div>
    </div>
      </div>
  </body>
</html>
