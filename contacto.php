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
            href="./recomendacion.php"
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
          <a
            class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700"
            href="./contacto.php"
          >
            <i class="fas fa-phone mr-2"> </i>
            Contacto
          </a>
        </nav>
      </div>

      <!-- Main Content -->
     <div class="container mx-auto mb-0 py-12 grid place-items-center">
        <h1 class="text-3xl font-bold mb-4">Highly Professional</h1>
        <p class="text-gray-600 mb-6 text-center">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas turpis nunc, efficitur a erat in, pulvinar vestibulum lectus. Nam efficitur commodo ligula. Cras volutpat, nisi non eleifend condimentum, orci massa blandit orci, ut suscipit dui mauris non sapien. Fusce ultrices suscipit est, eget placerat arcu. In hac habitasse platea dictumst. Pellentesque tincidunt aliquam nisi, ac laoreet turpis tincidunt non. Sed feugiat massa nec eros consectetur, quis volutpat sem tincidunt. Proin volutpat orci ut sollicitudin consectetur. Pellentesque aliquet nibh vel consectetur ullamcorper. Pellentesque congue mattis lobortis. Curabitur nibh sem, consequat eu elementum fringilla, rhoncus faucibus tortor. Sed quis lobortis dolor, vel condimentum magna. Morbi finibus velit sed pulvinar imperdiet. Mauris ultricies leo lacus, a egestas leo porttitor eu.

Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin neque justo, interdum vel volutpat eu, lacinia tristique massa. Sed sem sem, pulvinar sed ipsum id, aliquam facilisis libero. Suspendisse tortor mauris, placerat vitae lobortis in, lobortis ac ipsum. Pellentesque nibh mauris, viverra eu condimentum nec, faucibus a ante. Nunc ut augue lorem. Sed placerat viverra ipsum, sit amet tincidunt felis mollis sit amet. Suspendisse mi tortor, pulvinar et fermentum ut, gravida bibendum nulla. Vivamus condimentum, enim non rutrum facilisis, nisi tellus pretium magna, lacinia rutrum velit nisi vitae magna..</p>
        
       <hr class="w-full my-2 border-gray-300">
    <div class="flex justify-center space-x-8">
        <div class="text-center bg-gray-100 p-4 rounded">
            <h2 class="font-bold text-yellow-600 mb-2">Location:</h2>
            <p>45 Pirrama Rd,<br>Pyrmont NSW 2022</p>
        </div>
        <div class="text-center bg-gray-100 p-4 rounded">
            <h2 class="font-bold text-yellow-600 mb-2">Contacts:</h2>
            <p>info@bakery.com<br>(123) 123-1234</p>
        </div>
        <div class="text-center bg-gray-100 p-4 rounded">
            <h2 class="font-bold text-yellow-600 mb-2">Follow us:</h2>
            <p>Facebook<br>Instagram</p>
        </div>
    </div>
    </div>
    </div>
  </body>
</html>
