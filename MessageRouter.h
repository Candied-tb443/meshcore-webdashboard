#pragma once

#include "MeshCoreClient.h"

#include <string>

class MessageRouter
{
public:
    explicit MessageRouter(MeshCoreClient& client);

    void Attach();

private:
    void HandleMessage(const MeshCoreClient::RxMessage& msg, const std::string& fromName);

private:
    MeshCoreClient& m_client;
};