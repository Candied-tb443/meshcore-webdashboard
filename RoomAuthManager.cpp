#include "RoomAuthManager.h"

RoomAuthManager::State RoomAuthManager::GetState(uint32_t roomNodeId) const
{
    std::lock_guard<std::mutex> lock(m_mutex);

    const auto it = m_states.find(roomNodeId);

    if (it == m_states.end())
    {
        return State::Unknown;
    }

    return it->second;
}

void RoomAuthManager::SetLoginPending(uint32_t roomNodeId)
{
    std::lock_guard<std::mutex> lock(m_mutex);
    m_states[roomNodeId] = State::LoginPending;
}

void RoomAuthManager::SetLoggedIn(uint32_t roomNodeId)
{
    std::lock_guard<std::mutex> lock(m_mutex);
    m_states[roomNodeId] = State::LoggedIn;
}

void RoomAuthManager::SetLoginFailed(uint32_t roomNodeId)
{
    std::lock_guard<std::mutex> lock(m_mutex);
    m_states[roomNodeId] = State::LoginFailed;
}

void RoomAuthManager::Reset(uint32_t roomNodeId)
{
    std::lock_guard<std::mutex> lock(m_mutex);

    m_states.erase(roomNodeId);
    m_passwordRequested.erase(roomNodeId);

    if (m_pendingLoginRoomNodeId.has_value() &&
        *m_pendingLoginRoomNodeId == roomNodeId)
    {
        m_pendingLoginRoomNodeId.reset();
    }
}

void RoomAuthManager::ResetAll()
{
    std::lock_guard<std::mutex> lock(m_mutex);
    m_states.clear();
    m_pendingLoginRoomNodeId.reset();
    m_passwordRequested.clear();
}

bool RoomAuthManager::BeginLogin(uint32_t roomNodeId)
{
    std::lock_guard<std::mutex> lock(m_mutex);

    if (m_pendingLoginRoomNodeId.has_value())
    {
        if (*m_pendingLoginRoomNodeId == roomNodeId)
        {
            m_states[roomNodeId] = State::LoginPending;
            return true;
        }

        return false;
    }

    m_pendingLoginRoomNodeId = roomNodeId;
    m_states[roomNodeId] = State::LoginPending;
    return true;
}

std::optional<uint32_t> RoomAuthManager::ResolvePendingLoginSuccess()
{
    std::lock_guard<std::mutex> lock(m_mutex);

    if (!m_pendingLoginRoomNodeId.has_value())
    {
        return std::nullopt;
    }

    const uint32_t roomNodeId = *m_pendingLoginRoomNodeId;
    m_pendingLoginRoomNodeId.reset();
    m_states[roomNodeId] = State::LoggedIn;
    return roomNodeId;
}

std::optional<uint32_t> RoomAuthManager::ResolvePendingLoginFail()
{
    std::lock_guard<std::mutex> lock(m_mutex);

    if (!m_pendingLoginRoomNodeId.has_value())
    {
        return std::nullopt;
    }

    const uint32_t roomNodeId = *m_pendingLoginRoomNodeId;
    m_pendingLoginRoomNodeId.reset();
    m_states[roomNodeId] = State::LoginFailed;
    return roomNodeId;
}

bool RoomAuthManager::HasPassword(uint32_t roomNodeId) const
{
    std::lock_guard<std::mutex> lock(m_mutex);
    return m_passwords.find(roomNodeId) != m_passwords.end();
}

std::optional<std::string> RoomAuthManager::GetPassword(uint32_t roomNodeId) const
{
    std::lock_guard<std::mutex> lock(m_mutex);

    const auto it = m_passwords.find(roomNodeId);

    if (it == m_passwords.end())
    {
        return std::nullopt;
    }

    return it->second;
}

void RoomAuthManager::SetPassword(uint32_t roomNodeId, const std::string& password)
{
    std::lock_guard<std::mutex> lock(m_mutex);
    m_passwords[roomNodeId] = password;
    m_passwordRequested.erase(roomNodeId);
}

void RoomAuthManager::ClearPassword(uint32_t roomNodeId)
{
    std::lock_guard<std::mutex> lock(m_mutex);
    m_passwords.erase(roomNodeId);
}

bool RoomAuthManager::ShouldRequestPassword(uint32_t roomNodeId)
{
    std::lock_guard<std::mutex> lock(m_mutex);

    if (m_passwords.find(roomNodeId) != m_passwords.end())
    {
        return false;
    }

    return m_passwordRequested.find(roomNodeId) == m_passwordRequested.end();
}

void RoomAuthManager::MarkPasswordRequested(uint32_t roomNodeId)
{
    std::lock_guard<std::mutex> lock(m_mutex);
    m_passwordRequested.insert(roomNodeId);
}

void RoomAuthManager::ClearPasswordRequested(uint32_t roomNodeId)
{
    std::lock_guard<std::mutex> lock(m_mutex);
    m_passwordRequested.erase(roomNodeId);
}