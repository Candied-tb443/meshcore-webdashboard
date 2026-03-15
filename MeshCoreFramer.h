#pragma once

#include <cstdint>
#include <optional>
#include <vector>

class SerialPort;

class MeshCoreFramer
{
public:
    static constexpr uint8_t USB_INBOUND_PREFIX  = '<'; // PC -> Device
    static constexpr uint8_t USB_OUTBOUND_PREFIX = '>'; // Device -> PC

    explicit MeshCoreFramer(SerialPort &port);

    bool sendPayload(const std::vector<uint8_t> &payload);
    std::optional<std::vector<uint8_t>> readPayload(int timeoutMs);

    static uint16_t le16(uint8_t lo, uint8_t hi);

private:
    SerialPort &m_port;
};