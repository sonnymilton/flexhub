export interface Manifest {
    bundleClass: string;
    bundleEnv: string;
    copyFromRecipe: Record<string, string>;
    copyFromPackage: Record<string, string>;
    env: Record<string, string>;
    gitignore: Array<string>,
    composerScripts: Record<string, string>,
}

export interface RecipeListItem {
    packageName : string,
    vendor: string,
    versions: Array<string>
}

export interface File {
    path: string,
    content: string,
    executable: boolean,
}

export interface Recipe {
    vendor: string;
    packageName: string;
    version: string,
    manifest: Manifest,
    files: Array<File>
}
