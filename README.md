Ges Gestor de contenidos
========================

Se trata de una aplicación donde se genera un CRUD de forma dinámica mediante la configuración de algunos parametros.
No se trata de un CMS en sí ya que tod ala parte del Frontend no esta creada. El objetivo es poder crear webs sencillas del tipo de unas pocas páginas fijas con una o dos secciones dinámicas del tipo Noticias/Categorias, Servicios/Categorias, etc.

Es una aplicación Symfony2, se instala como si fuera una aplicación Symfony2 y se configuran lagunos parametros talcual se especifica en la wiki [Instalar Ges][1].


### Procedimiento de configuración

Una vez instalado debes eliminar las entidades de ejemplo. Gestor/CrudBundle/Entity/Categoria.php y Gestor/CrudBundle/Entity/Noticia.php

Crea tus entidades mediante la consola de Symfony2: 

    php app/console doctrine:generate:entity
    
Luego crea las relaciones si fuerán necesarias tanto entre tus entidades como si precisán imágenes en tal caso esta especificado en la Wiki: [Realción Entidades con imágenes][2]    

Sigue configurando los parametros para el menú del CRUD, los listados, etc. Todo esta descrito en la Wiki. [Wiki de Ges][3]


[1]:  https://github.com/Didweb/Ges/wiki/Instalaci%C3%B3n-de-Symfony2
[2]:  https://github.com/Didweb/Ges/wiki/Editar:-Configurar-y-Entidades-con-im%C3%A1genes
[3]:  https://github.com/Didweb/Ges/wiki
