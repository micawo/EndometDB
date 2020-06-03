EndometDB web frontend
======================
EndometDB web frontend works as the input/output layer for the EndometDB, providing user's query for the backend and plotting it after backend has finished processing it.

Installation
============
1. Install a web server with php support.
2. Create configuration for web server to serve the index.php of the frontend.
3. Run `npm run build` in directory `app`.
4. Create the file `include/constants.php` with the help of `include/constant.php.example`.
5. Ensure, that EndometDB backend has been started.

Contact information
===================
If you want to contribute to endometDB project or have questions, you can contact the EndometDB team:

- Matti Poutanen <matpou@utu.fi>
- Michael Gabriel <micawo@utu.fi>

Licensing
=========
endometDB is licensed under Apache License, Version 2.0. See LICENSE file for the full license text.

