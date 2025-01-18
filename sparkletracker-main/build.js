// C:\Users\sdkca\Desktop\electron-workspace\build.js
var electronInstaller = require("electron-winstaller");

// In this case, we can use relative paths
var settings = {
    // Specify the folder where the built app is located
    appDirectory: "./sparkle-tracker-win32-x64",
    // Specify the existing folder where
    outputDirectory: "./sparkle_tracker-installers",
    // The name of the Author of the app (the name of your company)
    authors: "Sparkle Infotech",
    // The name of the executable of your built
    exe: "./sparkle-tracker.exe",
    owners: "Sparkle Infotect",
    description: "It's Time Tracker APP.",
    version: "1.0.3"
};

resultPromise = electronInstaller.createWindowsInstaller(settings);

resultPromise.then(
    () => {
        console.log(
            "The installers of your application were succesfully created !"
        );
    },
    (e) => {
        console.log(`Well, sometimes you are not so lucky: ${e.message}`);
    }
);