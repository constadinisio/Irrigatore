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
      <div class="w-full max-w-4xl p-4">
        <iframe class="h-full w-full h-96 border" src="https://drive.google.com/file/d/1I2pcPLcAFps1QpZdbMmGm3O2d80WM4IB/preview" frameborder="0">
        </iframe>
    </div>
    </div>
  </body>
</html>