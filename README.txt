AttendAlert - Setup Instructions (XAMPP + VS Code)
====================================================

1. INSTALL XAMPP (if not already installed)
   Download from: https://www.apachefriends.org

2. COPY THE PROJECT FOLDER
   - Extract this zip file.
   - Copy the "AttendAlert" folder (the one containing index.html)
     into your XAMPP htdocs directory:
       Windows: C:\xampp\htdocs\
       macOS:   /Applications/XAMPP/htdocs/
       Linux:   /opt/lampp/htdocs/

   So the final path should look like:
       C:\xampp\htdocs\AttendAlert\index.html

3. START XAMPP
   - Open the XAMPP Control Panel.
   - Click "Start" next to Apache.

4. RUN IT IN THE BROWSER
   - Open your browser and go to:
       http://localhost/AttendAlert/

5. OPEN IN VS CODE (optional, for editing)
   - Open VS Code.
   - File > Open Folder > select the "AttendAlert" folder inside htdocs.
   - Install the "Live Server" extension if you also want to preview
     directly from VS Code without XAMPP (right-click index.html >
     "Open with Live Server").

NOTE: This is a static front-end prototype (HTML/CSS/JS only, no PHP
or database calls), so it does not strictly need Apache/PHP to run —
double-clicking index.html in a browser also works. XAMPP is only
needed if you plan to add PHP/MySQL functionality later.
