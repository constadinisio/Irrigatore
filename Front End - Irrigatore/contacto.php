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