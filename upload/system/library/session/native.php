<?php
namespace Session;
class Native extends \SessionHandler {
    public function create_sid(): string {
        return parent::create_sid();
    }

    public function open(string $path, $name): bool {
        return parent::open($path, $name);
    }

    public function close(): bool {
        return parent::close();
    }
	
    public function read($session_id): string {
        return parent::read($session_id);
    }

    public function write(string $session_id, $data): bool {
		return parent::write($session_id, $data);
    }

    public function destroy(string $session_id): bool {
        return parent::destroy($session_id);
    }

    public function gc($maxlifetime): int {
        return parent::gc($maxlifetime);
    }	
}