#pragma once

#include <cstdint>
#include <mutex>
#include <optional>
#include <string>
#include <unordered_map>
#include <unordered_set>

class RoomAuthManager
{
public:

    enum class State : uint8_t
    {
        Unknown = 0,
        LoginPending,
        LoggedIn,
        LoginFailed
    };

    State GetState(uint32_t roomNodeId) const;

    void SetLoginPending(uint32_t roomNodeId);
    void SetLoggedIn(uint32_t roomNodeId);
    void SetLoginFailed(uint32_t roomNodeId);

    void Reset(uint32_t roomNodeId);
    void ResetAll();

    bool BeginLogin(uint32_t roomNodeId);

    std::optional<uint32_t> ResolvePendingLoginSuccess();
    std::optional<uint32_t> ResolvePendingLoginFail();

    bool HasPassword(uint32_t roomNodeId) const;
    std::optional<std::string> GetPassword(uint32_t roomNodeId) const;
    void SetPassword(uint32_t roomNodeId, const std::string& password);
    void ClearPassword(uint32_t roomNodeId);

    bool ShouldRequestPassword(uint32_t roomNodeId);
    void MarkPasswordRequested(uint32_t roomNodeId);
    void ClearPasswordRequested(uint32_t roomNodeId);

private:

    mutable std::mutex m_mutex;
    std::unordered_map<uint32_t, State> m_states;
    std::optional<uint32_t> m_pendingLoginRoomNodeId;

    std::unordered_map<uint32_t, std::string> m_passwords;
    std::unordered_set<uint32_t> m_passwordRequested;
};