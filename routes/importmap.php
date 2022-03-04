<?php

use Tonysm\ImportmapLaravel\Facades\Importmap;

Importmap::pinAllFrom("resources/js", to: "js/", preload: true);

// Laravel's default scaffold ships with these dependencies...
Importmap::pin("lodash", to: "https://ga.jspm.io/npm:lodash@4.17.21/lodash.js", preload: true);
Importmap::pin("axios", to: "https://ga.jspm.io/npm:axios@0.21.4/index.js");
Importmap::pin("#lib/adapters/http.js", to: "https://ga.jspm.io/npm:axios@0.21.4/lib/adapters/xhr.js");
Importmap::pin("process", to: "https://ga.jspm.io/npm:@jspm/core@2.0.0-beta.14/nodelibs/browser/process-production.js");

// These also ships with Laravel's scaffold, but are commented out in the bootstrap.js file...
Importmap::pin("laravel-echo", to: "https://ga.jspm.io/npm:laravel-echo@1.11.3/dist/echo.js", preload: true);
Importmap::pin("pusher-js", to: "https://ga.jspm.io/npm:pusher-js@7.0.3/dist/web/pusher.js", preload: true);
Importmap::pin("alpinejs", to: "https://ga.jspm.io/npm:alpinejs@3.9.0/dist/module.esm.js");
Importmap::pin("@hotwired/turbo", to: "https://ga.jspm.io/npm:@hotwired/turbo@7.1.0/dist/turbo.es2017-esm.js");
Importmap::pin("@hotwired/stimulus", to: "https://ga.jspm.io/npm:@hotwired/stimulus@3.0.1/dist/stimulus.js");
Importmap::pin("trix", to: "https://ga.jspm.io/npm:trix@2.0.0-alpha.1/dist/trix.js");
Importmap::pin("@github/time-elements", to: "https://ga.jspm.io/npm:@github/time-elements@3.1.2/dist/index.js");
