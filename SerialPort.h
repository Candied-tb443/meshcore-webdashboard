#pragma once

#include <cstddef>
#include <cstdint>
#include <string>

class SerialPort
{
public:
    SerialPort();
    ~SerialPort();

    bool open(const std::string &device, int baud = 115200);
    void close();

    bool isOpen() const;
    int fd() const;

    bool writeAll(const uint8_t *data, size_t len);
    bool readExact(uint8_t *data, size_t len, int timeoutMs);

private:
    int m_fd;

    bool configure(int baud);
};