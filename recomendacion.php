<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Dashboard</title>
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
      <div class="w-64 bg-gray-800 text-white h-screen">
        <div class="p-4 flex items-center">
          <img
            alt="Logo"
            class="h-8 w-8 mr-2"
            height="40"
            src="https://drive.google.com/file/d/1p-9suCW6Kx5vaUO0yW9-U2GQjs87gewj/view"
            width="40"
          />
        </div>
        <nav class="mt-10">
          <a
            class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 mb-4"
            href="./dashboard.php"
          >
            <i class="fas fa-home mr-2"> </i>
            Dashboard
          </a>
          <div class="my-2 opacity-75">
            <p class="text-center text-sm">DATA</p>
            <div class="border-t border-gray-600 mt-1"></div>
          </div>
          <a
            class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700"
            href="#"
          >
            <i class="fas fa-plus mr-2"> </i>
            Recomendaciones
          </a>
          <a
            class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700"
            href="./team.php"
          >
            <i class="fas fa-users mr-2"> </i>
            ¿Quienes somos?
          </a>
          <a
            class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700"
            href="./documentation.php"
          >
            <i class="fas fa-book mr-2"> </i>
            Documentación
          </a>
          <a class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700" href="./contacto.php">
                    <i class="fas fa-phone mr-2">
                    </i>
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
