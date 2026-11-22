# Upgrading

## From 3.x to 4.0

### Requirements

- PHP 8.2 or later.
- `league/flysystem` 3.35.3 or later. Flysystem 1 and 2 are no longer
  supported; every release of them is affected by security advisory
  GHSA-cxf4-7mrp-vvpr.
- `symfony/process` 6.4.33+, 7.4.5+ or 8.0.5+ (releases patched for
  CVE-2024-51736 and CVE-2026-24739).

Install the Flysystem 3 adapter package for each filesystem type you use
(see the README). Notable package changes:

| Type | 3.x on Flysystem 1 | 4.0 |
|---|---|---|
| `Sftp` | `league/flysystem-sftp` | `league/flysystem-sftp-v3` |
| `Ftp` | built into Flysystem 1 | `league/flysystem-ftp` |
| `Gcs` | `superbalist/flysystem-google-storage` | `league/flysystem-google-cloud-storage` |

### Removed filesystem types

Their adapter packages only support Flysystem 1. They were deprecated in 3.2.0.

| Type | Class | Replacement |
|---|---|---|
| `DropboxV2` | `DropboxV2Filesystem` | `Dropbox` type with `spatie/flysystem-dropbox` |
| `B2` | `BackblazeFilesystem` | `AwsS3` type with B2's S3-compatible endpoint (`endpoint`, `use_path_style_endpoint`) |
| `Rackspace` | `RackspaceFilesystem` | none; register your own adapter via the `Flysystem` type |

Remove `add(new ...)` calls for these classes from your bootstrap.

### Config changes

- `Gcs`: the path prefix is the first non-empty of `prefix` and `root`, as in
  3.2 on Flysystem 2/3. On Flysystem 1, 3.x read only `prefix`; if you relied
  on `root` being ignored, remove it.
- `FlysystemFilesystem` no longer takes a `MountManager` second constructor
  argument, and the `prefix` config key is gone. A `MountManager` is a
  `FilesystemOperator`: register it by name and use `prefix://path`
  destination paths.

  ```php
  // 3.x
  new FlysystemFilesystem([], $mountManager);    // config: ['type' => 'Flysystem', 'prefix' => 'upload']
  // 4.0
  new FlysystemFilesystem(['mounts' => $mountManager]); // config: ['type' => 'Flysystem', 'name' => 'mounts']
  new Destination('flysystem', 'upload://backup.sql');
  ```

- `FlysystemFilesystem::get()` throws `InvalidArgumentException` for an
  unregistered name instead of returning `null`. Its constructor takes an
  `iterable` keyed by name; objects that are only `ArrayAccess` are no longer
  accepted.

### Code changes (only if you implement or call these directly)

- All classes use native parameter and return types and `strict_types`.
  Implementations of `Filesystem`, `Database`, `Compressor` and `Task` must
  match the new signatures:

  ```php
  interface Filesystem {
      public function handles(?string $type): bool;
      public function get(array $config): \League\Flysystem\FilesystemOperator;
  }
  interface Database {
      public function handles(?string $type): bool;
      public function setConfig(array $config): void;
      public function getDumpCommandLine(string $outputPath): string;
      public function getRestoreCommandLine(string $inputPath): string;
  }
  interface Compressor {
      public function handles(?string $type): bool;
      public function getCompressCommandLine(string $inputPath): string;
      public function getDecompressCommandLine(string $inputPath): string;
      public function getCompressedPath(string $inputPath): string;
      public function getDecompressedPath(string $inputPath): string;
  }
  interface Task {
      public function execute(): void;
  }
  ```

- If you subclass `Manager`, a procedure, a provider, `Config` or
  `ShellProcessor`, overriding methods must declare the new types, for example:

  ```php
  // Manager
  protected function getShellProcessor(): ShellProcessor;
  // BackupProcedure / RestoreProcedure
  public function run(string $database, array $destinations, ?string $compression): void;
  public function run(string $sourceType, string $sourcePath, string $databaseName, ?string $compression = null): void;
  // Procedure
  protected function getWorkingFile(string $name, ?string $filename = null): string;
  protected function getRootPath(string $name): string;
  // FilesystemProvider / DatabaseProvider / CompressorProvider
  public function get(string $name): FilesystemOperator; // Database; Compressor (?string $name)
  public function getConfig(string $name, ?string $key = null): mixed; // FilesystemProvider
  public function getAvailableProviders(): array;
  // Config
  public static function fromPhpFile(string $path): static;
  public function get(string $name, ?string $field = null): mixed;
  public function getItems(): array;
  // ShellProcessor
  public function process(Process $process): string;
  ```

- `Destination`'s constructor takes `(string $filesystem, string $path)`.
- `FilesystemProvider::get()` returns `League\Flysystem\FilesystemOperator`.
- `TransferFile` and `DeleteFile` take `FilesystemOperator` arguments.
  `FileTransferFailed`, `MountManagerFilesystem` (internal) and
  `FilesystemAdapterMissing::forFlysystemV1Type()` are removed; Flysystem 3 throws its own
  `FilesystemException`s (for example `UnableToWriteFile`).
- `DeleteFile::execute()` and the shell tasks return `void`.
- `Config`'s constructor is `final`.
- Filesystem factories, databases, compressors and tasks are `final`.
  Providers, `Config`, `Manager`, procedures, `ShellProcessor`, `Sequence`
  and exceptions remain extensible.
- `Manager::getShellProcessor()` returns a `ShellProcessor` built without
  arguments.

### Behaviour changes

- The MySQL restore command passes `-e 'source <path>'` as one shell-escaped
  argument; 3.x interpolated the path unescaped.
