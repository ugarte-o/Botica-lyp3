# meralda-thirdparty-modules
Third party libs and modules for Meralda

## About this submodule

This directory is a Git submodule containing third-party modules and libraries for Meralda. It replaces the old practice of distributing a single thirdparty zip file. All dependencies are now managed via Git for better version control and updates.

- **No more thirdparty zip:** All third-party code is now tracked as a submodule.
- **How to update:** Use `git submodule update --remote --merge src/mwap/modulesext` to update to the latest version.
- **Licensing:** Each module retains its original license. See the LICENSE file in each module for details.

## Cloning this submodule if you already cloned Meralda

If you cloned the main Meralda repository before the `modulesext` submodule was added, you need to initialize and fetch this submodule manually:

```bash
git submodule update --init src/mwap/modulesext
```

Or, to ensure all submodules are initialized:

```bash
git submodule update --init --recursive
```

This will fetch the latest third-party modules into `src/mwap/modulesext`.
