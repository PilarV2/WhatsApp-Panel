const {
    WAConnection,
    MessageType,
    MessageOptions,
    Presence,
    Mimetype,
    WALocationMessage,
    WA_MESSAGE_STUB_TYPES,
    ReconnectMode,
    ProxyAgent,
    waChatKey,
} = require("@adiwajshing/baileys");
const http = require("http");
const https = require("https");
var qrcode = require('qrcode');
const fs = require("fs");
const { body, validationResult } = require('express-validator');
const express = require('express');
const app = express();
const server = http.createServer(app);
const { Server } = require("socket.io");
//const socketIO = require('socket.io');
const { phoneNumberFormatter } = require('./helper/formatter');
const io = new Server(server);
//const io = socketIO(server);
// koneksi database
const mysql = require('mysql');
const request = require('request');
const { json } = require("express");
app.use(express.json());
app.use(express.urlencoded({ extended: true }));
const cron = require('node-cron');


app.get('*', function(req, res) {
    res.redirect('https://web-whatsapp-bot.herokuapp.com/wabot'); //ganti dengan url domain Anda
});

//konfigurasi koneksi
const db = mysql.createConnection({
  host: 'containers-us-west-66.railway.app',
  user: 'root',
  password: 'joxL3Sv8a5wN2RjoRGen',
  database: 'railway'
});

// sesuaikan dengan domain yang digunakan
const configs = {
    port: 80, // custom port to access server
    url_callback : 'https://web-whatsapp-bot.herokuapp.com/wabot/helper/callback.php'
};
// cronjob

cron.schedule('5 * * * *',  function() {
  console.log('cronjob berjalan')
 // console.log('ada init')
  const savedSessions = getSessionsFile();


      savedSessions.forEach(sess => {
          if(sess.ready == true){
console.log(sess.id)
              createSession(sess.id);
          }
      }); 
});
const sessions = [];
const SESSIONS_FILE = './whatsapp-sessions.json';
const mkZap = async (id) => {
  
    const conn =  new WAConnection()
     conn.version = [2, 2140, 12];
     await conn.loadAuthInfo(`./whatsapp-session-${id}.json`)
    if (conn.state == 'open'){
        return conn;
    } else {

        await conn.connect()
        return conn
    }

  }
const createSessionsFileIfNotExists = function () {
    if (!fs.existsSync(SESSIONS_FILE)) {
        try {
            fs.writeFileSync(SESSIONS_FILE, JSON.stringify([]));
            console.log('Sessions file created successfully.');
        } catch (err) {
            console.log('Failed to create sessions file: ', err);
        }
    } 
}
createSessionsFileIfNotExists();
const setSessionsFile = function (sessions) {
    fs.writeFile(SESSIONS_FILE, JSON.stringify(sessions), function (err) {
        if (err) {
            console.log(err);
        }
    });
} 
const getSessionsFile = function () {
    
    return JSON.parse(fs.readFileSync(SESSIONS_FILE));
}
const createSession = function (id) {

    const conn = new WAConnection();
    conn.version = [2, 2140, 12];
    conn.setMaxListeners(0);
    console.log('Creating session: ' + id);
    const SESSION_FILE_PATH = `./whatsapp-session-${id}.json`;
    let sessionCfg;
    if (fs.existsSync(SESSION_FILE_PATH)) {
        sessionCfg = require(SESSION_FILE_PATH);
        conn.loadAuthInfo(`./whatsapp-session-${id}.json`)
       if(conn.state == 'open'){
        io.emit('message', { id: id, text: 'Whatsapp is ready!' });
        io.emit('authenticated',  { id: id, data : conn.user})
        return conn;
        } else if( conn.state == 'connecting') {
            return;
    }
}

conn.on('qr', (qr) => {
    console.log('QR RECEIVED', qr);
    qrcode.toDataURL(qr, (err, url) => {
        io.emit('qr', { id: id, src: url });
        io.emit('message', { id: id, text: 'QR Code received, scan please!' });
    });
    conn.removeAllListeners('qr');
});

conn.connect(); 
// conn.on('initial-data-received',function(){
//     console.log('aaaaaaaaaaa')
// }) 
conn.on('open', (result) => {
    const session = conn.base64EncodedAuthInfo()
    fs.writeFile(SESSION_FILE_PATH, JSON.stringify(session), function (err) {
        if (err) {
            console.error(err);
        } else {
            console.log('berhasil buat');
        }
    });
    console.log(session);
    io.emit('ready', { id: id });
    io.emit('message', { id: id, text: 'Whatsapp is ready!' });
    io.emit('authenticated',  { id: id, data : conn.user})
    const savedSessions = getSessionsFile();
    const sessionIndex = savedSessions.findIndex(sess => sess.id == id);
    savedSessions[sessionIndex].ready = true;
    setSessionsFile(savedSessions);
    

});
// conn.on('initial-data-received',function(){
//     console.log('sfdsf');
// })
conn.on('close', ({ reason }) => {
	if (reason == 'invalid_session') {
    const nomors =  phoneNumberFormatter(conn.user.jid);
    const nomor = nomors.replace(/\D/g, '');
    console.log(nomor)
        if (fs.existsSync(`./whatsapp-session-${nomor}.json`)) {
            fs.unlinkSync(`./whatsapp-session-${nomor}.json`);
           
            io.emit('close', { id: nomor, text: 'Connection Lost..' });
            const savedSessions = getSessionsFile();
            const sessionIndex = savedSessions.findIndex(sess => sess.id == nomor);
            savedSessions[sessionIndex].ready = false;
            //setSessionsFile(savedSessions);
            setSessionsFile(savedSessions);          
	}
	}
})
// Menambahkan session ke file
// Tambahkan client ke sessions
sessions.push({
    id: id,
});
// Menambahkan session ke file
const savedSessions = getSessionsFile();
const sessionIndex = savedSessions.findIndex(sess => sess.id == id);

if (sessionIndex == -1) {
    savedSessions.push({
        id: id,
        ready: false,
    });
    setSessionsFile(savedSessions);
}

conn.on('initial-data-received', async () => {
    console.log('initialize');
    request({ url: configs.url_callback, method: "POST", json: {"id" : conn.user.jid ,"data" : conn.contacts} })
})

// chat masuk
conn.on('chat-update', async chat => {
    if(chat.messages && chat.count){
        const m = chat.messages.all()[0] // pull the new message from the update
        let sender = m.key.remoteJid
        const messageContent = m.message
        const messageType = Object.keys(messageContent)[0]
if(messageType == 'conversation' ){
    var text = m.message.conversation
} else if(messageType == 'extendedTextMessage' ){
   var text = m.message.extendedTextMessage.text
} else if(messageType == 'imageMessage'){
   var text = m.message.imageMessage.caption
}
const numb =  phoneNumberFormatter(conn.user.jid);
const mynumb = numb.replace(/\D/g, '');
//hook
let sqlhook = `SELECT link_webhook FROM device WHERE nomor = ${mynumb} `;
db.query(sqlhook, function (err, result) {
    if (err) throw err;
       const webhookurl = result[0].link_webhook;
       const pesan = {
           sender: phoneNumberFormatter(sender),
           msg: text
       }
      kirimwebhook(sender, text, m,conn,webhookurl);
});
// end hook
//autoreply
let sqlautoreply = `SELECT * FROM autoreply WHERE keyword = "${text}" AND nomor = "${mynumb}"`;
db.query(sqlautoreply, function (err, result) {
    if (err) throw err;
    result.forEach(data => {
             if(data.media == ''){
                 conn.sendMessage(sender, data.response, MessageType.text);
             } else {
                 var media = `${data.media}`;
                 const ress = data.response
              const array = media.split(".");
              const ext = array[array.length - 1];
                 if(ext == 'jpg' || ext == 'png'){
                     let options = { mimetype: 'image/jpeg' , caption: ress, filename: "file.jpeg" };
                     conn.sendMessage(sender, {url: media}, MessageType.image, options);
                 } else if (ext == 'pdf'){
                    const getlink = media.split("/");
                    const namefile = getlink[getlink.length - 1]
                    const link = `./pages/uploads/${namefile}`
                    conn.sendMessage(sender, { url: link }, MessageType.document, { mimetype: Mimetype['pdf'],filename : namefile })
                 }
             }
});
});
          }
   })
}
//init
const init = function (socket) {
    const savedSessions = getSessionsFile();
        savedSessions.forEach(sess => {
            if(sess.ready == true){
console.log(sess.id)
                createSession(sess.id);
            }
        });
  }
  
  init();
// koneksi socket
io.on('connection', function (socket) {
    init(socket);
// membuat session
    socket.on('create-session', function (data) {
        console.log(data)
        console.log('Create session: ' + data.id);
        createSession(data.id);
    });
//
    // ini baris untuk logout
    socket.on('logout',async function (data) {
        if (fs.existsSync(`./whatsapp-session-${data.id}.json`)) {
            socket.emit('isdelete', { id : data.id, text :'<h2 class="text-center text-info mt-4">Logout Success, Lets Scan Again<h2>' })
          fs.unlinkSync(`./whatsapp-session-${data.id}.json`);
            const savedSessions = getSessionsFile();
            const sessionIndex = savedSessions.findIndex(sess => sess.id == data.id);
            savedSessions[sessionIndex].ready = false;
            //setSessionsFile(savedSessions);
            setSessionsFile(savedSessions);
        } else {
            socket.emit('isdelete', { id : data.id, text : '<h2 class="text-center text-danger mt-4">You are have not Login yet!<h2>'})
        }
    })
    // 
});

// Send message
app.post('/send-message', async (req, res) => {
    const sender = req.body.sender;
    if (fs.existsSync(`whatsapp-session-${sender}.json`)) {
    const client = await mkZap(sender);
    
   // var number = phoneNumberFormatter(req.body.number);
    const message = req.body.message;
    if (req.body.number.length > 15) {
		var number = req.body.number; 
    } else {
        var number = phoneNumberFormatter(req.body.number);
        var numberExists = await client.isOnWhatsApp(number);
		if (!numberExists) {
			return res.status(422).json({
				status: false,
				message: 'The number is not registered'
			});
		}
    }

 if(client.state == 'open'){
    client.sendMessage(number, message, MessageType.text).then(response => {
        res.status(200).json({
            status: true,
            response: response
        });
    }).catch(err => {
        res.status(500).json({
            status: false,
            response: err
        });
    });
    } else {
        res.status(500).json({
            status: false,
            response: 'Please scan the QR before use this API'
        });
    }
} else {
    res.writeHead(401, {
        'Content-Type': 'application/json'
    });
    res.end(JSON.stringify({
        status: false,
        message: 'Please scan the QR before use the API 2'
    }));
}
}); 

// send media
app.post('/send-media', async (req, res) => {
    const sender = req.body.sender;
    if (fs.existsSync(`whatsapp-session-${sender}.json`)) {
        const client = await mkZap(sender);
        const url = req.body.url;
        const filetype = req.body.filetype;
        const filename = req.body.filename;
        const caption = req.body.caption;
  //  var number = phoneNumberFormatter(req.body.number);
  //  const message = req.body.message;
    if (req.body.number.length > 18) {
		var number = req.body.number; 
    } else {
        var number = phoneNumberFormatter(req.body.number);
        var numberExists = await client.isOnWhatsApp(number);
		if (!numberExists) {
			return res.status(422).json({
				status: false,
				message: 'The number is not registered'
			});
		}
    }

  
if(client.state == 'open'){
 if (filetype == 'jpg' || filetype == 'png') {
       console.log(filetype)
        let options = { mimetype: 'image/jpeg' , caption: caption, filename: filename };
        client.sendMessage(number, {url: url}, MessageType.image, options).then(response => {
            res.status(200).json({
                status: true,
                response: response
            });
        }).catch(err => {
            res.status(500).json({
                status: false,
                response: err
            });
        });

    } else if (filetype == 'pdf') {
        client.sendMessage(number, { url: url }, MessageType.document, { mimetype: Mimetype['pdf'],filename : filename + '.pdf' }).then(response => {
            return res.status(200).json({
                status: true,
                response: response
            });
        }).catch(err => {
            return res.status(500).json({
                status: false,
                response: err
            });
        });
    } else {
        res.status(500).json({
            status: false,
            response: 'Filetype tidak dikenal'
        });
    }
    } else {
        res.status(500).json({
            status: false,
            response: 'Please scan the QR before use this API'
        });
    }
} else {
    res.writeHead(401, {
        'Content-Type': 'application/json'
    });
    res.end(JSON.stringify({
        status: false,
        message: 'Please scan the QR before use the API 2'
    }));
}
});



//function kebutuhan webhook
function kirimwebhook(sender, message, m ,conn,link) {
   
	var webhook_response = {
		from: phoneNumberFormatter(sender),
		message: message
	}
	const getBuffer = async (url, options) => {
		try {
			options ? options : {}
			const res = await axios({
				method: "get",
				url,
				...options,
				responseType: 'arraybuffer'
			})
			return res.data
		} catch (e) {
			console.log(`Error : ${e}`)
		}
	}

	request({ url: link, method: "POST", json: webhook_response },
		async function (error, response) {
			if (!error && response.statusCode == 200) {
				// process hook
				if (response.body == null) {
					return 'gagal send webhook';
				}
				const res = response.body;
				console.log(res);
				if (res.mode == 'chat') {
					conn.sendMessage(sender, res.pesan, MessageType.text)
				} else if (res.mode == 'reply') {
					conn.sendMessage(sender, res.pesan, MessageType.extendedText, { quoted: m })
				} else if (res.mode == 'picture') {
					const url = res.data.url;
					const caption = res.data.caption;
					var messageOptions = {};
					const buffer = await getBuffer(url);
					if (caption != '') messageOptions.caption = caption;
					conn.sendMessage(sender, buffer, MessageType.image, messageOptions);
				}
			} else { console.log('error'); }
		}
	);
}



server.listen(configs.port, function () {
    console.log('App running on *: ' + configs.port);
});


