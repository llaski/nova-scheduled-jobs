export default function jobsFromResponse(payload) {
    if (Array.isArray(payload)) {
        return payload
    }

    if (payload && Array.isArray(payload.data)) {
        return payload.data
    }

    return []
}
