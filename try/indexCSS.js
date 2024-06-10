import express from "express";
import { fileURLToPath } from "url";
import path from "path";
import mysql from "mysql";

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

const app = express();
const port = 3000;


const connection = mysql.createConnection({
  host: "sql6.freesqldatabase.com",
  user: "sql6705819",
  password: "hLLrdKwXFB",
  database: "sql6705819"
});


connection.connect((err) => {
  if (err) {
    console.error("Error connecting to MySQL database:", err);
    return;
  }
  console.log("Connected to MySQL database");
});

app.use(express.urlencoded({ extended: true }));

app.set("view engine", "ejs");
app.set("views", path.join(__dirname, "views"));


app.use(express.static(path.join(__dirname, "public")));
app.use(express.static(__dirname + "/public/club.html"));


app.get("/", (req, res) => {
  res.render("loginCSS");
});

app.post('loginCSS/submit', (req, res) => {
  const inputValue = req.body.role;
  if (inputValue === 'PIC') {
      res.render('/dashPIC'); 
  } else {
      res.render('/dashboardCSS'); 
  }
});

app.post("/dashboardCSS", (req, res) => {
  const id = req.body.id;
  res.render("dashboardCSS", { id });
});

app.post("/dashPIC",(req,res) =>{
  try {
    const id=req.body.id;
    connection.query("SELECT pic_id,pic_name FROM pic WHERE pic_id = ?;", [id], (error1, results1) => {
      if (error1) {
        console.error("Error querying pic table:", error1);
        return res.status(500).send("Internal Server Error");
      }

      if (results1.length === 0) {
        console.error("PIC not found");
        return res.status(404).send("PIC Not Found");
      }

      //console.log("PIC data:", results1);

      const pic_name = results1[0].pic_name;

      connection.query("SELECT id,name,type FROM clubs WHERE pic_id = ?;", [id], (error2, results2) => {
        if (error2) {
          console.error("Error querying clubs table:", error2);
          return res.status(500).send("Internal Server Error");
        }

        //console.log("Clubs data:", results2); 
        //rendering
      
      connection.query(" select c.name,e.event_name,p.id from clubs c join events e on c.id=e.club_id join participates p on e.event_id=p.event_id where c.pic_id=?;",[id],(error3,results3)=>{
        if(error3){
          console.error("Error querying participates table :",error3);
          return res.status(500).send("Internal Server Error");
        }
        res.render('dashPIC', { 
          pic_name: pic_name, 
          pic_id: id, 
          club_id: results2.map(result => result.id),
          club_name: results2.map(result => result.name),
          club_type: results2.map(result => result.type),
          cname: results3.map(result => result.name),
          ename: results3.map(result => result.event_name),
          pid:   results3.map(result => result.id)
        });
      })
      });
       
    });
  } catch (error) {
    console.error("Error in /dashPIC route:", error);
    res.status(500).send("Internal Server Error");
  }
});




app.post("/profileCSS", (req, res) => {
  const id = req.body.id;

  const userQuery = "SELECT name, email FROM people WHERE ID = ?";

  const eventsQuery = `
    SELECT e.event_id, e.event_name 
    FROM events e 
    JOIN participates p ON e.event_id = p.event_id 
    WHERE p.ID = ?
  `;

  const clubQuery = `
    SELECT m.club_id, m.position, m.subsystem_id
    FROM members m
    WHERE m.ID = ?
  `;
  connection.query(userQuery, [id], (error1, results1) => {
    if (error1) {
      console.error("Error querying people table:", error1);
      return res.status(500).send("Internal Server Error");
    }

    if (results1.length === 0) {
      return res.status(404).send("User Not Found");
    }

    connection.query(eventsQuery, [id], (error2, results2) => {
      if (error2) {
        console.error("Error querying events table:", error2);
        return res.status(500).send("Internal Server Error");
      }

      connection.query(clubQuery, [id], (error3, results3) => {
        if (error3) {
          console.error("Error querying members table:", error3);
          return res.status(500).send("Internal Server Error");
        }
        //rendering
        res.render('profileCSS', { 
          name: results1[0].name, 
          email: results1[0].email, 
          events: results2,
          clubs: results3
        });
      });
    });
  });
});


app.get('/club', function(req, res) {
  res.sendFile(__dirname + "/public/club.html");
});



 

app.listen(port, () => {
  console.log(`Server is running on port ${port}`);
});









