<!DOCTYPE html>
<html lang='en'>
  <head>
    <meta charset='utf-8' />
    <link rel="stylesheet" href="plug-ins/fullcalendar/lib/main.css"/>
    <script src='plug-ins/fullcalendar/lib/main.js'></script>
    <script>

      document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
          initialView: 'dayGridMonth'
        });
        calendar.render();
      });

    </script>
  </head>
  <body>
  <?php include 'includes/navbar.php';?>
    <header>
      <img src="images/checklist.png" alt="pencil on a clipboard" />
      <h1>My Sign Ups</h1>
    </header>
    <main>
    <section>
      <div id='calendar'></div>
    </section>
    </main>
    
  </body>
</html>