// Modules to control application life and create native browser window
const {
    app,
    BrowserWindow,
    ipcMain
} = require("electron");
const path = require("path");
const env = require("dotenv");
const {
    session
} = require("electron");
const Store = require("electron-store");
const store = new Store();
const request = require("request");
const {
    autoUpdater
} = require('electron-updater');
let child;
let mainWindow;
let feedback;


function createWindow() {
    console.log('toke ', store.get("api_token"));
    if (typeof (store.get("mainScreen")) != "undefined" && store.get("mainScreen") !== null && store.get("mainScreen") == 1) {
        if (typeof (store.get("api_token")) != "undefined" && store.get("api_token") !== null && store.get('api_token') !== '') {
            console.log('-------11111111--===', `Bearer ${store.get("api_token")}`);
            // create check login api send username and API token 
            request.get({
                url: "http://localhost/laravel/time-tracker/api/check-user",
                headers: {
                    Authorization: `Bearer ${store.get("api_token")}`,
                },
            },
                function callback(err, response, body) {
                    if (err) {
                        child = new BrowserWindow({
                            width: 350,
                            height: 635,
                            maxWidth: 350,
                            maxHeight: 635,
                            minWidth: 350,
                            minHeight: 635,
                            frame: true,
                            menuBarVisible: false,
                            resizable: false,
                            fullscreen: false,
                            icon: "img/icon.ico",
                            webPreferences: {
                                preload: path.join(__dirname, "preload.js"),
                                contextIsolation: false,
                                enableRemoteModule: true,
                                nodeIntegration: true,
                                nodeIntegrationInWorker: true
                            },
                        });
                        child.setMenuBarVisibility(false);
                        child.loadFile("login.html");
                        return console.error("Failed to upload:", err);
                    }
                    else if (response && response.body) {
                    console.log('response check-user ', response.body);
                        var obj = JSON.parse(response.body);
                        console.log('obj', obj.message);
                        if (obj && obj.status == 200) {
                            const data = obj.data;
                            // store.set("imageUrl", data.imageUrl);
                            // store.set("last_sctime", data.last_sctime);
                            // store.set("trackerMemo", data.trackerMemo);
                            // store.set("todayTotalTime", data.todayTotalTime);
                            // store.set("weekTotalTime", data.weekTotalTime);
                            mainWindow = new BrowserWindow({
                                width: 350,
                                height: 635,
                                maxWidth: 350,
                                maxHeight: 635,
                                minWidth: 350,
                                minHeight: 635,
                                frame: true,
                                menuBarVisible: false,
                                resizable: false,
                                fullscreen: false,
                                icon: "img/icon.ico",
                                webPreferences: {
                                    preload: path.join(__dirname, "preload.js"),
                                    contextIsolation: false,
                                    enableRemoteModule: true,
                                    nodeIntegration: true,
                                    nodeIntegrationInWorker: true
                                },
                            });
                            mainWindow.setMenuBarVisibility(false);
                            mainWindow.loadFile("index.html");

                            mainWindow.once('ready-to-show', () => {
                                autoUpdater.checkForUpdatesAndNotify();
                            });

                        } else {
                            child = new BrowserWindow({
                                width: 350,
                                height: 635,
                                maxWidth: 350,
                                maxHeight: 635,
                                minWidth: 350,
                                minHeight: 635,
                                frame: true,
                                menuBarVisible: false,
                                resizable: false,
                                fullscreen: false,
                                icon: "img/icon.ico",
                                webPreferences: {
                                    preload: path.join(__dirname, "preload.js"),
                                    contextIsolation: false,
                                    enableRemoteModule: true,
                                    nodeIntegration: true,
                                    nodeIntegrationInWorker: true
                                },
                            });
                            child.setMenuBarVisibility(false);
                            child.loadFile("login.html");
                        }
                    }else{
                        child = new BrowserWindow({
                            width: 350,
                            height: 635,
                            maxWidth: 350,
                            maxHeight: 635,
                            minWidth: 350,
                            minHeight: 635,
                            frame: true,
                            menuBarVisible: false,
                            resizable: false,
                            fullscreen: false,
                            icon: "img/icon.ico",
                            webPreferences: {
                                preload: path.join(__dirname, "preload.js"),
                                contextIsolation: false,
                                enableRemoteModule: true,
                                nodeIntegration: true,
                                nodeIntegrationInWorker: true
                            },
                        });
                        child.setMenuBarVisibility(false);
                        child.loadFile("login.html");
                    }
                });
        }
    } else {
        console.log('------22222222222222');

        child = new BrowserWindow({
            width: 350,
            height: 635,
            maxWidth: 350,
            maxHeight: 635,
            minWidth: 350,
            minHeight: 635,
            frame: true,
            menuBarVisible: false,
            resizable: false,
            fullscreen: false,
            icon: "img/icon.ico",
            webPreferences: {
                preload: path.join(__dirname, "preload.js"),
                contextIsolation: false,
                enableRemoteModule: true,
                nodeIntegration: true,
                nodeIntegrationInWorker: true
            },
        });
        child.setMenuBarVisibility(false);
        child.loadFile("login.html");
    }
}

ipcMain.on("entry-accepted", (event, arg) => {
    console.log('-------33333333333333');

    if (arg == "ping") {
        console.log('-------344444444444444');

        mainWindow = new BrowserWindow({
            width: 350,
            height: 635,
            maxWidth: 350,
            maxHeight: 635,
            minWidth: 350,
            minHeight: 635,
            frame: true,
            menuBarVisible: false,
            resizable: false,
            fullscreen: false,
            icon: "img/icon.ico",
            webPreferences: {
                preload: path.join(__dirname, "preload.js"),
                contextIsolation: false,
                enableRemoteModule: true,
                nodeIntegration: true,
                nodeIntegrationInWorker: true
            },
        });
        mainWindow.setMenuBarVisibility(false);
        mainWindow.loadFile("index.html");

        mainWindow.once('ready-to-show', () => {
            autoUpdater.checkForUpdatesAndNotify();
        });
        child.destroy();
    }
    if (arg == "logout") {
        console.log('-------55555555555555555555555');

        child = new BrowserWindow({
            width: 350,
            height: 635,
            maxWidth: 350,
            maxHeight: 635,
            minWidth: 350,
            minHeight: 635,
            frame: true,
            menuBarVisible: false,
            resizable: false,
            fullscreen: false,
            icon: "img/icon.ico",
            webPreferences: {
                preload: path.join(__dirname, "preload.js"),
                contextIsolation: false,
                enableRemoteModule: true,
                nodeIntegration: true,
                nodeIntegrationInWorker: true
            },
        });
        child.setMenuBarVisibility(false);
        child.loadFile("login.html");
        mainWindow.destroy();
    }
    if (arg == "feedback") {
        feedback = new BrowserWindow({
            width: 350,
            height: 635,
            maxWidth: 350,
            maxHeight: 635,
            minWidth: 350,
            minHeight: 635,
            frame: true,
            menuBarVisible: false,
            resizable: false,
            fullscreen: false,
            icon: "img/icon.ico",
            webPreferences: {
                preload: path.join(__dirname, "preload.js"),
                contextIsolation: false,
                enableRemoteModule: true,
                nodeIntegration: true,
                nodeIntegrationInWorker: true
            },
        });
        feedback.setMenuBarVisibility(false);
        feedback.loadFile("feedback.html");
        mainWindow.destroy();
    }
    if (arg == "feedback-cancel") {
        mainWindow = new BrowserWindow({
            width: 350,
            height: 635,
            maxWidth: 350,
            maxHeight: 635,
            minWidth: 350,
            minHeight: 635,
            frame: true,
            menuBarVisible: false,
            resizable: false,
            fullscreen: false,
            icon: "img/icon.ico",
            webPreferences: {
                preload: path.join(__dirname, "preload.js"),
                contextIsolation: false,
                enableRemoteModule: true,
                nodeIntegration: true,
                nodeIntegrationInWorker: true
            },
        });
        mainWindow.setMenuBarVisibility(false);
        mainWindow.loadFile("index.html");
        mainWindow.once('ready-to-show', () => {
            autoUpdater.checkForUpdatesAndNotify();
        });
        feedback.destroy();
    }
});

ipcMain.on('app_version', (event) => {
    event.sender.send('app_version', {
        version: app.getVersion()
    });
});

autoUpdater.on('update-available', () => {
    mainWindow.webContents.send('update_available');
});

autoUpdater.on('update-progress', (progress) => {
    console.log(`Progress ${Math.floor(progress.percent)}`);
});

autoUpdater.on('update-downloaded', () => {
    mainWindow.webContents.send('update_downloaded');
});

ipcMain.on('restart_app', () => {
    autoUpdater.quitAndInstall();
});

app.whenReady().then(() => {
    app.allowRendererProcessReuse = false;
    createWindow();
    app.on("activate", function () {
        if (BrowserWindow.getAllWindows().length === 0)
            createWindow();
    });
});
// Quit when all windows are closed.
app.on("window-all-closed", function () {
    if (process.platform !== "darwin")
        app.quit();
});

// this should be placed at top of main.js to handle setup events quickly
if (handleSquirrelEvent()) {
    // squirrel event handled and app will exit in 1000ms, so don't do anything else
    return;
}

function handleSquirrelEvent() {
    if (process.argv.length === 1) {
        return false;
    }

    const ChildProcess = require('child_process');
    const path = require('path');

    const appFolder = path.resolve(process.execPath, '..');
    const rootAtomFolder = path.resolve(appFolder, '..');
    const updateDotExe = path.resolve(path.join(rootAtomFolder, 'Update.exe'));
    const exeName = path.basename(process.execPath);

    const spawn = function (command, args) {
        let spawnedProcess, error;

        try {
            spawnedProcess = ChildProcess.spawn(command, args, {
                detached: true
            });
        } catch (error) { }

        return spawnedProcess;
    };

    const spawnUpdate = function (args) {
        return spawn(updateDotExe, args);
    };

    const squirrelEvent = process.argv[1];
    switch (squirrelEvent) {
        case '--squirrel-install':
        case '--squirrel-updated':
            // Optionally do things such as:
            // - Add your .exe to the PATH
            // - Write to the registry for things like file associations and
            //   explorer context menus

            // Install desktop and start menu shortcuts
            spawnUpdate(['--createShortcut', exeName]);

            setTimeout(app.quit, 1000);
            return true;

        case '--squirrel-uninstall':
            // Undo anything you did in the --squirrel-install and
            // --squirrel-updated handlers

            // Remove desktop and start menu shortcuts
            spawnUpdate(['--removeShortcut', exeName]);

            setTimeout(app.quit, 1000);
            return true;

        case '--squirrel-obsolete':
            // This is called on the outgoing version of your app before
            // we update to the new version - it's the opposite of
            // --squirrel-updated

            app.quit();
            return true;
    }
}