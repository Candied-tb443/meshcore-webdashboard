#pragma once

#include "MeshCoreClient.h"
#include "AppRuntime.h"

#include <cstdint>
#include <vector>

class PushRouter
{
public:
    PushRouter(MeshCoreClient& client, AppRuntime& runtime);

    void Attach();

private:
    void HandlePush(uint8_t code, const std::vector<uint8_t>& payload);

    void HandleAdvert(const std::vector<uint8_t>& payload);
    void HandlePathUpdated(const std::vector<uint8_t>& payload);
    void HandleSendConfirmed(const std::vector<uint8_t>& payload);
    void HandleMsgWaiting(const std::vector<uint8_t>& payload);
    void HandleLoginSuccess(const std::vector<uint8_t>& payload);
    void HandleLoginFail(const std::vector<uint8_t>& payload);
    void HandleLogRxData(const std::vector<uint8_t>& payload);
    void HandleTraceData(const std::vector<uint8_t>& payload);
    void HandleNewAdvert(const std::vector<uint8_t>& payload);
    void HandleUnknown(uint8_t code, const std::vector<uint8_t>& payload);

private:
    MeshCoreClient& m_client;
    AppRuntime& m_runtime;
};