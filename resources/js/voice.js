let vadInstance = null

window.VAD = {
    async toggle({ recipeId, csrfToken, getCurrentStep, onMatch, onStatus, onToggle }) {
        if (vadInstance) {
            vadInstance.destroy()
            vadInstance = null
            onToggle(false)
            onStatus('')
            return
        }

        onStatus('Opstarten...')

        try {
            const { MicVAD, utils } = await import('@ricky0123/vad-web')

            vadInstance = await MicVAD.new({
                baseAssetPath:   '/vad/',
                onnxWASMBasePath: '/vad/',
                model:           'v5',
                onSpeechStart() {
                    onStatus('Luisteren...')
                },
                async onSpeechEnd(audio) {
                    onStatus('Verwerken...')

                    try {
                        const wav  = utils.encodeWAV(audio)
                        const blob = new Blob([wav], { type: 'audio/wav' })
                        const form = new FormData()
                        form.append('audio', blob, 'speech.wav')
                        form.append('_token', csrfToken)
                        const step = getCurrentStep ? (getCurrentStep() ?? 0) : 0
                        form.append('current_step', step)

                        const res  = await fetch(`/recepten/${recipeId}/voice`, { method: 'POST', body: form })
                        if (!res.ok) throw new Error(`HTTP ${res.status}`)
                        const data = await res.json()

                        if (data.wakeword_found) {
                            if (data.timestamp !== null && data.timestamp !== undefined) {
                                onMatch(data.timestamp, data.step)
                                onStatus(data.step ? `Stap ${data.step} gevonden` : 'Gevonden')
                            } else {
                                onStatus(data.command ? `"${data.command}" — niet gevonden` : 'Niet begrepen')
                            }
                            setTimeout(() => onStatus('Zeg "Hey Hapklaar..."'), 3000)
                        } else {
                            onStatus('Zeg "Hey Hapklaar..."')
                        }
                    } catch {
                        onStatus('Fout opgetreden')
                        setTimeout(() => onStatus('Zeg "Hey Hapklaar..."'), 2000)
                    }
                },
            })

            vadInstance.start()
            onToggle(true)
            onStatus('Zeg "Hey Hapklaar..."')
        } catch {
            onStatus('Microfoon toegang geweigerd')
            onToggle(false)
        }
    },
}
