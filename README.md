# 📊 meshcore-webdashboard - View mesh radio data on screen

[![Download / Visit Project](https://img.shields.io/badge/Download%20%2F%20Visit-Project-blue?style=for-the-badge)](https://github.com/Candied-tb443/meshcore-webdashboard)

## 🖥️ What this is

meshcore-webdashboard is a web dashboard for MeshCore radio nodes. It helps you view PC monitor data from a Linux PC or a Raspberry Pi in a browser. You can use it to check node status, message activity, signal details, and device info from one simple screen.

This project fits hobby radio setups that use LoRa hardware, small Linux computers, and mesh network tools. It is meant for people who want a clear view of what their mesh radio device is doing without opening complex tools.

## 📥 Download or visit the project page

Use this link to visit the project page and get the files you need:

[meshcore-webdashboard on GitHub](https://github.com/Candied-tb443/meshcore-webdashboard)

On that page, look for the latest release files or the main project files. If the project provides a packaged app for Windows, download that file first. If it only provides source files, use the setup steps below with a small Linux system or Raspberry Pi.

## 🪟 Run on Windows

This dashboard is built for Linux PCs and Raspberry Pi setups, but many users begin on Windows to read the project files and prepare their setup.

If you are on Windows and want to use the dashboard workflow:

1. Visit the project page above.
2. Find the latest release or package.
3. Download the file that matches your system.
4. If the project gives you an installer or app file, open it after the download finishes.
5. If the project gives you source files only, use a Linux PC, a Raspberry Pi, or a virtual machine that runs Linux.
6. Open the dashboard in your browser once it is running.

If you plan to connect the dashboard to a radio device, make sure the device is already linked to the Linux system or Raspberry Pi that runs the service.

## 🔧 What you need

A simple setup usually needs:

- A Windows PC for viewing and setup
- A Linux PC or Raspberry Pi for running the dashboard
- A modern browser like Chrome, Edge, or Firefox
- A MeshCore radio device or compatible LoRa setup
- A working network link between the browser and the Linux device

If you use a Raspberry Pi, a wired network connection can help keep the dashboard stable. A small screen works well for a desk setup, while a browser tab on a laptop gives you more room for status panels.

## 🚀 Install and start

Follow these steps in order:

1. Open the project page here: [https://github.com/Candied-tb443/meshcore-webdashboard](https://github.com/Candied-tb443/meshcore-webdashboard)
2. Look for a release file, app package, or setup instructions.
3. Download the file that matches your device.
4. If you use Windows only for viewing, keep the download for reference and move to the Linux or Raspberry Pi device that will run the dashboard.
5. Copy the project files to the Linux PC or Raspberry Pi.
6. Open the project folder.
7. Start the dashboard using the command or launch method given by the project files.
8. Open your browser and go to the local address shown by the app.
9. Keep the browser open while you use the dashboard.

If the project includes a ready-made start script, use that first. If it includes a web app that runs on a local port, copy that address into your browser.

## 📡 How it helps in daily use

This dashboard is useful when you want to:

- Check whether your mesh node is online
- See radio activity in one place
- Watch messages as they move through the mesh
- Review signal and device details
- Keep a monitor view open during a radio session
- Use one screen for simple control and status checks

For a home radio desk, this makes it easier to keep track of the network without moving between tools. For a small field setup, it gives you a clear view of the device state on a local screen.

## 🧭 Basic setup flow

A common setup looks like this:

1. Connect the LoRa or MeshCore hardware to the Linux PC or Raspberry Pi.
2. Install the project files on that device.
3. Start the dashboard service.
4. Open the web page in a browser.
5. Confirm that the node status and radio data appear.
6. Leave the page open while the device runs.

If your device uses a USB radio board, plug it in before you start the app. If it uses a serial link, check that the correct port is selected. If the dashboard uses a config file, keep the settings simple at first and change one item at a time.

## 🧰 Common parts of the dashboard

Most users will see sections like these:

- Status area for the current node
- Message list for recent traffic
- Device info panel
- Signal or link details
- Connection state for the local radio link
- Settings page for basic options

Some builds may show extra data for LoRa settings, mesh peers, or hardware status. If you use a Raspberry Pi, the dashboard may also show system details such as uptime or resource use.

## 🧪 First run checks

After you start the app, check these items:

- The browser page opens
- The node shows as connected
- The message area updates
- The radio device appears in the device list
- The page stays live after refresh
- No blank panels remain on screen

If something looks wrong, restart the dashboard and check the USB cable, serial port, or network link. Many setup issues come from the wrong port or a missing device connection.

## 📁 File layout you may see

The project may include folders such as:

- `src` for app code
- `public` for browser files
- `config` for settings
- `scripts` for start files
- `assets` for icons or images
- `docs` for notes and setup help

If you are not sure which file to run, look for a start file with names like `start`, `run`, `launch`, or `install`. On Linux or Raspberry Pi, those files often start the dashboard with one command.

## 🔌 Hardware notes

This project works best with a setup that uses:

- A Linux PC or Raspberry Pi
- A supported LoRa radio board
- A stable USB cable
- A clean power source
- A browser on the same network

For Heltec and SX1262 based builds, check that the board is wired and powered the right way. If the radio board uses serial data, the app must point to the right device path. If the dashboard depends on a local service, keep that service running before you open the page.

## 🗂️ Topics and use case fit

This project matches common mesh and radio setups tied to:

- hamradio
- heltec
- linux
- lora
- mesh
- meshcom
- meshcore
- meshtastic
- radio
- raspberry
- sx1262

That makes it a good fit for home labs, radio desks, and small field stations that need a web view of mesh traffic on a nearby screen.

## 🛠️ Troubleshooting

If the page does not load:

- Check that the app is running
- Make sure you opened the right local address
- Try refreshing the browser
- Restart the Linux PC or Raspberry Pi
- Check that the radio device is still connected

If the dashboard opens but shows no data:

- Confirm the mesh device is powered on
- Check the USB or serial link
- Use the correct port name
- Make sure the radio firmware matches the dashboard
- Restart the service after changing settings

If the browser shows an error:

- Close other tabs and try again
- Test another browser
- Clear the page cache
- Check that the local service still runs on the device

## 📦 Simple setup checklist

- Visit the project page
- Download the project files
- Copy them to a Linux PC or Raspberry Pi
- Connect the radio hardware
- Start the dashboard
- Open the browser page
- Check that data appears
- Leave the screen open while using the mesh node

## 🔗 Project link

[https://github.com/Candied-tb443/meshcore-webdashboard](https://github.com/Candied-tb443/meshcore-webdashboard)

## 🧾 License and use

Use the files in the project as provided by the repository owner. Check the repository page for the current license, build notes, and file list before you install or modify anything