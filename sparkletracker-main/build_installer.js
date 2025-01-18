// ./build_installer.js

// 1. Import Modules
const {
    MSICreator
} = require('electron-wix-msi');
const path = require('path');

// 2. Define input and output directory.
// Important: the directories must be absolute, not relative e.g
// appDirectory: "C:\\Users\sdkca\Desktop\OurCodeWorld-win32-x64", 
const APP_DIR = path.resolve(__dirname, './sparkle-tracker-win32-x64');
// outputDirectory: "C:\\Users\sdkca\Desktop\windows_installer", 
const OUT_DIR = path.resolve(__dirname, './windows_installer');

// 3. Instantiate the MSICreator
const msiCreator = new MSICreator({
    appDirectory: APP_DIR,
    outputDirectory: OUT_DIR,

    // Configure metadata
    description: 'It is Time Tracker APP.',
    exe: 'sparkle-tracker',
    name: 'Sparkle Tracker',
    manufacturer: 'Sparkle Infotech',
    version: '1.0.0',
    shortName: 'tracker',
    shortcutName: 'Sparkle Tracker',
    DesktopShortcutGuid: "*",
    icon: path.resolve(__dirname, './img/icon.ico'),
    setupIcon: path.resolve(__dirname, './img/icon.ico'),
    appIconPath: path.resolve(__dirname, './img/icon.ico'),
    ChangePermission: 'Yes',
    ReadPermission: 'Yes',
    WriteAttributes: 'Yes',
    Write: 'Yes',
    User: "Everyone",
    features: {
        autoUpdate: true,
        autoLaunch: true
    },
    // Configure installer User Interface
    ui: {
        enabled: true,
        chooseDirectory: true,
        images: {
            infoIcon: path.resolve(__dirname, './img/icon.ico')
        }
    }
});

// 4. Create a .wxs template file
msiCreator.create().then(function() {

    // Step 5: Compile the template to a .msi file
    msiCreator.compile();
});