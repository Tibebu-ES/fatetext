# FATEtext, Version 0.6

"hosting for decentralized open source online communities since 2021"

FATEtext (aka. FATE) is an open source (MIT License) reference implementation for a novel approach to hosting online communities that does not necessarily rely on the 1990s era liability shield for US Internet companies known as, "Section 230."

I tested this version of FATE (0.6) on my personal computer using MacOS and MAMP (5.7), which runs PHP (7.4.2), but I avoided using any Javascript, and future versions of FATE will not use cookies for session management, etc.

Please see the file samplehtaccess.txt for the URL rewriting rules that I added to my webserver's .htaccess file during testing.

The about page template (which mostly consists of plain HTML) in templates/about.php includes more information about what you can do once you get an instance of FATE up and running.

MIT License

Copyright (c) 2021 Todd Perry

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
